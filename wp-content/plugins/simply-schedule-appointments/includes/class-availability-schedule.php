<?php
/**
 * Simply Schedule Appointments Availability Schedule.
 *
 * @since   3.6.10
 * @package Simply_Schedule_Appointments
 */

use League\Period\Period;

/**
 * Simply Schedule Appointments Availability Schedule.
 *
 * @since 3.6.10
 */
class SSA_Availability_Schedule implements Countable, Iterator {
	/**
	 * Parent plugin class.
	 *
	 * @since 3.6.10
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	protected $blocks = array();
	protected $is_sorted = true;

	protected $new_blocks = array();
	protected $count = 0;
	protected $position = 0;

	/**
	 * Constructor.
	 *
	 * @since  3.6.10
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct() {

	}

	public function set_blocks( $blocks, $is_clean = false ) {
		$clone = $this->get_clone();
		$clone->blocks = $blocks;
		$clone->is_sorted = $is_clean;

		return $clone;
		// $this->is_clean = $is_clean; // sorted, gapless, overlapless
	}

	public function get_blocks() {
		if ( $this->is_sorted() ) {
			return $this->blocks;
		}

		$this->blocks = $this->sort()->blocks;
		$this->is_sorted = true;

		return $this->blocks;
	}

	public function overlaps( SSA_Availability_Schedule $schedule ) {
		$this_boundaries = $this->boundaries();
		if ( empty( $this_boundaries ) ) {
			return false;
		}

		$schedule_boundaries = $schedule->boundaries();
		if ( empty( $schedule_boundaries ) ) {
			return false;
		}

		return $this_boundaries->overlaps( $schedule_boundaries );
	}

	public function subrange( Period $period ) {
		if ( $this->boundaries() == $period ) {
			return $this;
		}

		$filter_function = function ( $value ) use ( $period ) {
			if ( $value->get_period()->overlaps( $period ) ) {
				return true;
			}

			return false;
		};

		return $this->filter( $filter_function );
	}

	// public function blackout_by_capacity_reserved_gte( int $capacity_reserved ) {
	// 	$schedule = $this->set_blocks(array(), true);
	// 	foreach ($this->get_blocks() as $block) {
	// 		echo '<pre>'.print_r($block, true).'</pre>';
	// 		if ( $block->capacity_reserved >= $capacity_reserved ) {
	// 			$block->capacity_available = 0;
	// 			$schedule = $schedule->pushmerge( $block );
	// 		}
	// 	}

	// 	return $schedule;
	// }

	/**
	 * Filters the sequence according to the given predicate.
	 *
	 * This method MUST retain the state of the current instance, and return
	 * an instance that contains the interval which validate the predicate.
	 */
	public function filter( callable $predicate ) {
		$blocks = $this->get_blocks();
		$filtered_blocks = array_filter( $blocks, $predicate, ARRAY_FILTER_USE_BOTH );
		if ( $filtered_blocks === $blocks ) {
			return $this;
		}

		return $this->set_blocks( $filtered_blocks );
	}

	/**
	 * Returns an instance sorted according to the given comparison callable
	 * but does not maintain index association.
	 *
	 * This method DOES NOT retain the state of the current instance, it sorts the original
	 */
	public function sort( callable $compare = null ) {
		if ( $this->is_sorted() ) {
			return $this;
		}

		if ( null === $compare ) {
			$compare = array( $this, 'sort_by_start_date' );
		}
		$blocks = $this->blocks;
		usort($blocks, $compare);
		if ($blocks === $this->blocks) {
			$this->is_sorted = true;
			return $this;
		}

		$new_instance = $this->set_blocks( $blocks, true );
		return $new_instance;
	}

	private function get_clone() {
		$instance = clone $this;
		$instance->_queried_appointments = null;

		return $instance;
	}

	public function boundaries() {
		if ( $this->is_empty() ) {
			return null;
		}

		$blocks = $this->get_blocks();
		if ( 1 === count( $blocks ) ) {
			return $blocks[0]->get_period();
		}


		$start_date = array_shift( $blocks )->get_period()->getStartDate();
		$end_date = array_pop( $blocks )->get_period()->getEndDate();

		return new Period( $start_date, $end_date );
	}

	/**
	 * Sorts two Interval instance using their start datepoint.
	 */
	private function sort_by_start_date(SSA_Availability_Block $block1, SSA_Availability_Block $block2) {
		$a = $block1->get_period()->getStartDate();
		$b = $block2->get_period()->getStartDate();

		if ( $a == $b ) {
			return 0;
		} else if ( $a < $b ) {
			return -1;
		} else if ( $a > $b ) {
			return 1;
		}

		return null;
	}

	private function reconcile_new_blocks() {
		if ( empty( $this->new_blocks ) || ! is_array( $this->new_blocks ) ) {
			return;
		}

		foreach ($this->new_blocks as $key => $block) {
			$schedule = $this->add_block( $block );
		}
	}

	public function add_block( SSA_Availability_Block $new_block ) {
		$boundaries = $this->boundaries();

		// Let's try to handle the simple (non-overlapping) cases first
		if ( $this->is_empty() ) {
			$reconciled_schedule = $this->push( $new_block );

			return $reconciled_schedule;
		} else if ( $new_block->is_after_period( $boundaries ) ) {
			$reconciled_schedule = $this->pushmerge( $new_block );
			return $reconciled_schedule;
		} else if ( $new_block->is_before_period( $boundaries ) ) {
			$reconciled_schedule = $this->unshiftmerge( $new_block );
			return $reconciled_schedule;
		} else if ( ! $new_block->overlaps_period( $boundaries ) ) {
			throw new Exception("unhandled case in add_block()", 1);
		}

		// we must be dealing with an overlapping block (the complex case)
		$reconciled_schedule = $this->add_overlapping_block( $new_block );

		return $reconciled_schedule;
	}

	private function add_overlapping_block( SSA_Availability_Block $new_block ) {
		$reconciled_schedule = $this->set_blocks( array() );
		$has_added_reconciled_blocks = false;

		foreach ($this->get_blocks() as $key => $original_block) {
			if ( $has_added_reconciled_blocks ) {
				$reconciled_schedule = $reconciled_schedule->add_block( $original_block );
				continue;
			}

			if ( ! $has_added_reconciled_blocks &&
				 ! $original_block->get_period()->overlaps( $new_block->get_period() )
			) {
				$reconciled_schedule = $reconciled_schedule->push( $original_block );
				continue;
			}

			$reconciled_blocks = array();

			if ( $original_block->contains( $new_block ) ) {
				// Add Contained Block
				$left_block = $original_block;
				$right_block = $original_block;
			} else if ( $new_block->contains( $original_block ) ) {
				// Add Container Block
				$left_block = $new_block;
				$right_block = $new_block;
			} else if ( $new_block->overlaps( $original_block ) ) {
				if ( $new_block->get_period()->getStartDate() < $original_block->get_period()->getStartDate() ) {
					// Add overlapping earlier block
					$left_block = $new_block;
					$right_block = $original_block;
				} else if ( $new_block->get_period()->getStartDate() >= $original_block->get_period()->getStartDate() ) {
					// Add overlapping later block
					$left_block = $original_block;
					$right_block = $new_block;
				}
			} else {
				// we shouldn't even be in this function unless the blocks overlap
				throw new Exception("unhandled case in add_overlapping_block()", 1);
			}

			$middle_block = null;

			$diff_array = $original_block->get_period()->diff( $new_block->get_period() );

			$intersect_period = $original_block->get_period()->intersect( $new_block->get_period() );


			if ( ! empty( $diff_array[0] ) ) {			
				$reconciled_blocks[] = $left_block->set_period( $diff_array[0] );
			}

			if ( ! empty( $intersect_period ) ) {			
				$reconciled_blocks[] = $this->reconcile_overlapping_blocks_intersection(
					$original_block,
					$new_block,
					$intersect_period
				);
			}

			if ( ! empty( $diff_array[1] ) ) {
				$reconciled_blocks[] = $right_block->set_period( $diff_array[1] );
			}

			$schedule_to_merge = $this->set_blocks( $reconciled_blocks )->cleaned();

			$reconciled_schedule = $reconciled_schedule->merge( $schedule_to_merge );

			$has_added_reconciled_blocks = true;
		}

		return $reconciled_schedule;
	}

	public function reconcile_overlapping_blocks_intersection( SSA_Availability_Block $original_block, SSA_Availability_Block $new_block, Period $intersect_period ) {

		$intersect_block = new SSA_Availability_Block();
		$intersect_block->period = $intersect_period;

		$intersect_block->capacity_reserved = max(
			$original_block->capacity_reserved,
			$new_block->capacity_reserved
		);
		$intersect_block->capacity_available = min(
			$original_block->capacity_available,
			$new_block->capacity_available
		);

		$capacity_reserved_delta = $original_block->capacity_reserved_delta + $new_block->capacity_reserved_delta;
		


		$intersect_block->buffer_reserved = max(
			$original_block->buffer_reserved,
			$new_block->buffer_reserved
		);
		$intersect_block->buffer_available = min(
			$original_block->buffer_available,
			$new_block->buffer_available
		);

		$buffer_reserved_delta = $original_block->buffer_reserved_delta + $new_block->buffer_reserved_delta;

		if ( $intersect_block->capacity_available < SSA_Constants::CAPACITY_MAX ) {
			$intersect_block->capacity_available -= $capacity_reserved_delta;
			$intersect_block->capacity_reserved += $capacity_reserved_delta;

		}

		if ( $intersect_block->buffer_available < SSA_Constants::CAPACITY_MAX ) {
			$intersect_block->buffer_available -= $buffer_reserved_delta;
			$intersect_block->buffer_reserved += $buffer_reserved_delta;
		}

		return $intersect_block;
	}

	public function get_criteria() {

	}

	public function is_empty() {
		return array() === $this->blocks;
	}

	public function is_sorted() {
		return $this->is_sorted;
	}

	public function is_valid() {
		if ( empty( $this->blocks ) ) {
			return false;
		}

		$last_block = null;
		foreach ($this->get_blocks() as $key => $block) {
			if ( ! empty( $last_block ) ) {
				if ( ! $block->abuts( $last_block ) ) {
					return false;
				}
			}

			$last_block = $block;
		}

		return true;
	}

	/**
	 * Adds new blocks at the end of the sequence.
	 *
	 * @param array of SSA_Availability_Block $blocks
	 */
	public function push( $blocks ) {
		if ( ! is_array( $blocks ) ) {
			$blocks = array( $blocks );
		}

		$clone = $this->get_clone();
		$clone->blocks = array_merge($clone->blocks, $blocks);
		$clone->is_sorted = false;

		return $clone;
	}

	/**
	 * Adds new blocks at the end of the sequence.
	 *
	 * @param array of SSA_Availability_Block $blocks
	 */
	public function pushmerge( $new_blocks ) {
		if ( ! is_array( $new_blocks ) ) {
			$new_blocks = array( $new_blocks );
		}

		$remaining_new_blocks = $new_blocks;
		$first_new_block = array_shift( $remaining_new_blocks );
		if ( empty( $first_new_block ) ) {
			return $this;
		}

		$reconciled_schedule_blocks = $this->get_blocks();
		$last_block = array_pop( $reconciled_schedule_blocks );
		if ( empty( $last_block ) ) {
			return $this->push( $new_blocks );
		}

		if ( ! $last_block->can_merge( $first_new_block ) ) {
			return $this->push( $new_blocks );
		}

		$reconciled_schedule = $this->set_blocks( $reconciled_schedule_blocks );
		$reconciled_schedule = $reconciled_schedule->push( $last_block->merge( $first_new_block ) );
		if ( empty( $remaining_new_blocks ) ) {
			return $reconciled_schedule;
		}

		$reconciled_schedule = $reconciled_schedule->push( $remaining_new_blocks );

		return $reconciled_schedule;
	}

	/**
	 * Adds new blocks at the beginning of the sequence (and does the extra work to prevent abutting mergeable blocks)
	 *
	 * @param array of SSA_Availability_Block $blocks
	 */
	public function unshift( $blocks ) {
		if ( ! is_array( $blocks ) ) {
			$blocks = array( $blocks );
		}

		$clone = $this->get_clone();
		$clone->blocks = array_merge( $blocks, $clone->blocks );
		$clone->is_sorted = false;

		return $clone;
	}

	/**
	 * Adds new blocks at the beginning of the sequence (and does the extra work to prevent abutting mergeable blocks)
	 *
	 * @param array of SSA_Availability_Block $blocks
	 */
	public function unshiftmerge( $new_blocks ) {
		if ( ! is_array( $new_blocks ) ) {
			$new_blocks = array( $new_blocks );
		}

		$remaining_new_blocks = $new_blocks;
		$last_new_block = array_shift( $remaining_new_blocks );
		if ( empty( $last_new_block ) ) {
			return $this;
		}

		$reconciled_schedule_blocks = $this->get_blocks();
		$first_old_block = array_shift( $reconciled_schedule_blocks );
		if ( empty( $first_old_block ) ) {
			return $this->unshift( $new_blocks );
		}

		if ( ! $first_old_block->can_merge( $last_new_block ) ) {
			return $this->unshift( $new_blocks );
		}

		$reconciled_schedule = $this->set_blocks( $reconciled_schedule_blocks );
		$reconciled_schedule = $reconciled_schedule->unshift( $first_old_block->merge( $last_new_block ) );
		if ( empty( $remaining_new_blocks ) ) {
			return $reconciled_schedule;
		}

		$reconciled_schedule = $reconciled_schedule->unshift( $remaining_new_blocks );

		return $reconciled_schedule;
	}

	public function merge( SSA_Availability_Schedule $another_schedule ) {
		if ( null === $another_schedule ) {
			return $this;
		}

		$another_schedule_blocks = $another_schedule->get_blocks();
		if ( empty( $another_schedule_blocks ) ) {
			return $this;
		}

		if ( empty( $this->get_blocks() ) ) {
			return $another_schedule;
		}

		
		$merged_schedule = $this->get_clone();
		foreach ( $another_schedule_blocks as $block) {
			$merged_schedule = $merged_schedule->add_block( $block );
		}

		return $merged_schedule;
	}

	public function cleaned() {
		$last_block = null;
		$reduced_blocks = array();

		foreach ($this->get_blocks() as $block) {
			$last_block = array_pop( $reduced_blocks );

			if ( empty( $last_block ) ) {
				$reduced_blocks[] = $block;
				continue;
			}

			if ( ! $last_block->can_merge( $block ) ) {
				$reduced_blocks[] = $last_block;
				$reduced_blocks[] = $block;
				continue;
			}

			$reduced_blocks[] = $last_block->merge( $block );
		}

		return $this->set_blocks( $reduced_blocks );
	}

	public function get_blocks_for_period( Period $period ) {
		$overlapping_blocks = array();
		foreach ($this->get_blocks() as $key => $block) {
			if ( $block->get_period()->overlaps( $period ) ) {
				$overlapping_blocks[] = $block;
			}
		}

		return $overlapping_blocks;
	}

	public function get_block_for_date( $date ) {
		$boundary_period = $this->boundaries();
		if ( empty( $boundary_period ) || ! $boundary_period->contains( $date ) ) {
			return false;
		}

		foreach ($this->get_blocks() as $key => $block) {
			if ( $block->get_period()->contains( $date ) ) {
				return $block;
			}
		}

		return false;
	}

	public function get_free_busy_schedule( SSA_Appointment_Type_Object $appointment_type = null, $minimum_free_capacity = 1 ) {
		$schedule = new SSA_Availability_Schedule();

		foreach ( $this->get_blocks() as $block ) {
			$block = $block->set_capacity_available( min( $block->capacity_available, $minimum_free_capacity ) );
			$block = $block->set_capacity_reserved( min( $block->capacity_reserved, $minimum_free_capacity ) );

			$block = $block->set_buffer_available( min( $block->buffer_available, $minimum_free_capacity ) );
			$block = $block->set_buffer_reserved( min( $block->buffer_reserved, $minimum_free_capacity ) );

			if ( $block->capacity_available >= $minimum_free_capacity && ( ! empty( $appointment_type ) ) ) {
				if ( 'group' === $appointment_type->capacity_type ) {
					if ( $block->capacity_reserved > 0 ) {
						$block = $block->set_capacity_available( 0 );
					}
				}
			}

			$schedule = $schedule->pushmerge( $block );
		}

		return $schedule;
	}

	/**
	 * Iteratively reduces the sequence to a single value using a callback.
	 *
	 * @param callable $func Accepts the carry, the current value, and
	 *     returns an updated carry value.
	 *
	 * @param mixed|null $carry Optional initial carry value.
	 *
	 * @return mixed The carry value of the final iteration, or the initial
	 *               value if the sequence was empty.
	 */
	public function reduce(callable $func, $carry = null) {
		foreach ($this->intervals as $offset => $interval) {
			$carry = $func($carry, $interval, $offset);
		}

		return $carry;
	}



	/* Countable */
	public function count() {
		return count( $this->blocks );
	}


	/* Iterator */
	public function rewind() {
		$this->position = 0;
	}

	public function current() {
		return $this->blocks[$this->position];
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		++$this->position;
	}

	public function valid() {
		return isset($this->blocks[$this->position]);
	}
}
