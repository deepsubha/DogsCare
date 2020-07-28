/* global PetClinicScreenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

jQuery(function($){
 	"use strict";
   	jQuery('.main-menu-navigation > ul').superfish({
	delay:       500,
	animation:   {opacity:'show',height:'show'},  
	speed:       'fast'
   });
});

function the_pet_clinic_open() {
	document.getElementById("sidelong-menu").style.width = "250px";
}
function the_pet_clinic_close() {
	document.getElementById("sidelong-menu").style.width = "0";
}