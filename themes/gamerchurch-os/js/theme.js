/*-- DATE --*/
function tCal() {
	var d = new Date();
	var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
	var months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
	var s = d.getDate();
	var sday = ('00'+s).slice(-2);
	document.getElementById("getDate").innerHTML = days[d.getDay()] + " " + months[d.getMonth()] + "/" + sday;
}

tCal();

/*-- CLOCK --*/
function currentTime() {
	var date = new Date();
	var hour = date.getHours();
	var min = date.getMinutes();
	var sec = date.getSeconds();
	hour = updateTime(hour);
	min = updateTime(min);
	sec = updateTime(sec);
	document.getElementById("clock").innerText = hour + ":" + min + ":" + sec;
	var t = setTimeout(function() {
		currentTime()
	}, 1000); 
}

function updateTime(k) {
	if (k < 10) {
		return "0" + k;
	} else {
		return k;
	}
}

currentTime();

/*-- FPS --*/
let be = Date.now(),
	fps = 0;
requestAnimationFrame(
	function loop() {
		let now = Date.now()
		fps = Math.round(1000 / (now - be))
		be = now
		requestAnimationFrame(loop)
		kFps.textContent = "FPS:" + fps;
	}
)


/*-- CURRENT KEY --*/
function currentKey() {
	var keyStrokes = ["Ctrl C", "Alt", "Ctrl Shift", "Down", "Delete", "Shift F11", "Up", "Return", "Alt P", "Ctrl Delete", "Ins", "Esc", "Shift PrtScrn1", "Alt Shift A", "Down", "Right","Ctrl Left", "F5", "Space"];
	var keyPressed = keyStrokes[Math.floor(Math.random() * keyStrokes.length)];
	document.getElementById("keyStroke").innerHTML = keyPressed;
	var tk = setTimeout(function() {
		currentKey()
	}, Math.floor(Math.random() * 2000));
}

currentKey();

/*-- DEBUG BINARY --*/
function findRandom() {
         let num =
         (1 + parseInt((Math.random() * 100))) % 2;
         return num;
}

function generateBinaryString(N) {
	let S = "";
	for (let i = 0; i < N; i++) {
		let x = findRandom();
		S += (x).toString();
	}
	document.getElementById('debugFeed').innerHTML+="<span>"+S+"</span> ";
	var t = setTimeout(function() {
		generateBinaryString(N)
	}, 100);
}
 
let N =  8;
generateBinaryString(N);

function resetDebug() {
	var debugArray = ["3Y35 ;", "34R5 ;", "P41NT ;", "C0L0R5 ;"];
	var debugString = debugArray[Math.floor(Math.random() * debugArray.length)];
	document.getElementById('debugFeed').innerHTML=debugString+"<br>";
	var t = setTimeout(function() {
		resetDebug(N)
	}, 11000);
}

resetDebug();

/*-- COL MANAGEMENT --*/

function closeCol(colName) {
	document.getElementById(colName).style.display='none';
};

function openCol(colName) {
	document.getElementById(colName).style.display='block';
};


/*-- DARK MODE --*/

function setCookie(cname,cvalue) {
        document.cookie = cname+"="+cvalue+"; path=/";
        window.location.reload();
}

window.onload = function () {
    const cookies = document.cookie.split(';').map((cookie) => cookie.trim());
    const isDarkMode = cookies.some((item) => item === 'darkMode=1');
    const isLightMode = cookies.some((item) => item === 'darkMode=0');

    const htmlElement = document.documentElement;
    const btnDark = document.getElementById('btnDark');
    const btnLight = document.getElementById('btnLight');
    const pageElement = document.getElementById('page');

    if (isDarkMode) {
        htmlElement.classList.add('darkmode');
        btnDark.classList.add('hidden');
        btnLight.classList.remove('hidden');
    } else if (isLightMode) {
        pageElement.classList.remove('darkmode');
        btnDark.classList.remove('hidden');
        btnLight.classList.add('hidden');
    }
};
