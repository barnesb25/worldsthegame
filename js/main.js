/****************************************************************************
* Name:        main.js
* Author:      Ben Barnes
* Date:        2015-12-28
* Purpose:     Client-side error checking and interface enhancing.
*****************************************************************************/
/* Main Body Loading */
function bodyLoad() {
	$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		})
} // bodyLoad

function loadButton(element1) {
	element1.innerHTML = "<span class=\"glyphicon glyphicon-refresh glyphicon-refresh-animate\"></span> Loading...";
} // loadButton