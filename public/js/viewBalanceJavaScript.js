
function isALeapYear(year) {
	if((year % 4 == 0) && (year % 100 != 0) || (year % 400 == 0)) {
		return true;
	}
	else {
		return false;
	}
}

function getLastDayOfProvidedYearAndMonth(year, month) {
	if(month < 8) {
		if(month == 2) {
			if(isALeapYear(year)) return 29;
			else return 28;
		}
		else if(month % 2 == 1) return 31;
		else return 30;
	}
	else if(month >= 8) {
		if(month % 2 == 1) return 30;
		else 31;
	}
}

function getFirstDayTextOfProvidedDate(date) {
	var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
	
	var presentMonth = date.getMonth() + 1; // January is 0!
	
	if(presentMonth < 10) {
		var firstDayText = date.getFullYear() + "-0" + presentMonth + "-0" + firstDay.getDate();
	}
	else {
		var firstDayText = date.getFullYear() + "-" + presentMonth + "-0" + firstDay.getDate();
	}
	
	return firstDayText;
}

function getLastDayTextOfProvidedDate(date) {
	var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0); // January is 0!
	
	var presentMonth = date.getMonth() + 1; // January is 0!
	
	if(presentMonth < 10) {
		var lastDayText = date.getFullYear() + "-0" + presentMonth + "-" + lastDay.getDate();
	}
	else {
		var lastDayText = date.getFullYear() + "-" + presentMonth + "-" + lastDay.getDate();
	}
	
	return lastDayText;
}

function getFirstDayTextOfPresentMonthDate() {
	var today = new Date();
	
	return getFirstDayTextOfProvidedDate(today);
}

function getLastDayTextOfPresentMonthDate() {
	var today = new Date();

	return getLastDayTextOfProvidedDate(today);
}

function inputPresentMonthDate(dateFromInputId = "dateFrom", dateToInputId = "dateTo") {	
	document.getElementById(dateFromInputId).value = getFirstDayTextOfPresentMonthDate();
	document.getElementById(dateToInputId).value = getLastDayTextOfPresentMonthDate();
}

function inputPreviousMonthDate(dateFromInputId = "dateFrom", dateToInputId = "dateTo") {
	var today = new Date();
	var firstDay = new Date(today.getFullYear(), today.getMonth() - 1, 1);
	var lastDay = new Date(today.getFullYear(), today.getMonth(), 0); // January is 0!
	
	var previousMonth = today.getMonth() + 1 - 1 ; // - 1 because of today.getMonth()
	
	if(previousMonth < 10) {
		var firstDayText = today.getFullYear() + "-0" + previousMonth + "-0" + firstDay.getDate();
		var lastDayText = today.getFullYear() + "-0" + previousMonth + "-" + lastDay.getDate();
	}
	else {
		var firstDayText = today.getFullYear() + "-" + previousMonth + "-0" + firstDay.getDate();
		var lastDayText = today.getFullYear() + "-" + previousMonth + "-" + lastDay.getDate();
	}
	
	document.getElementById(dateFromInputId).value = firstDayText;
	document.getElementById(dateToInputId).value = lastDayText;
}

function inputPresentYearDate(dateFromInputId = "dateFrom", dateToInputId = "dateTo") {
	var today = new Date();
	var firstDay = new Date(today.getFullYear(), 0, 1); // January is 0!
	var lastDay = new Date(today.getFullYear() + 1, 0, 0); // January is 0!
	
	var firstMonth = firstDay.getMonth() + 1; //
	var lastMonth = lastDay.getMonth() + 1; //
	
	if(firstMonth < 10) {
		var firstDayText = today.getFullYear() + "-0" + firstMonth + "-0" + firstDay.getDate();
	}
	else {
		var firstDayText = today.getFullYear() + "-" + firstMonth + "-0" + firstDay.getDate();
		
	}
	if(lastMonth < 10) {
		var lastDayText = today.getFullYear() + "-0" + lastMonth + "-" + lastDay.getDate();
	}
	else {
		var lastDayText = today.getFullYear() + "-" + lastMonth + "-" + lastDay.getDate();
	}
	
	document.getElementById(dateFromInputId).value = firstDayText;
	document.getElementById(dateToInputId).value = lastDayText;
}

function returnPresentMonthFirstDay() {
	var today = new Date();
	var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
	
	var presentMonth = today.getMonth() + 1; // January is 0!
	
	if(presentMonth < 10) {
		var firstDayText = today.getFullYear() + "-0" + presentMonth + "-0" + firstDay.getDate();
	}
	else {
		var firstDayText = today.getFullYear() + "-" + presentMonth + "-0" + firstDay.getDate();
	}
	
	return firstDayText;
}

function returnPresentMonthLastDay() {
	var today = new Date();
	var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0); // January is 0!
	
	var presentMonth = today.getMonth() + 1; // January is 0!
	
	if(presentMonth < 10) {
		var lastDayText = today.getFullYear() + "-0" + presentMonth + "-" + lastDay.getDate();
	}
	else {
		var lastDayText = today.getFullYear() + "-" + presentMonth + "-" + lastDay.getDate();
	}
	
	return lastDayText;
}
