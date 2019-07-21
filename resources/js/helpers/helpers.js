/**
 * Helpers Functions
 */
import moment from 'moment';

// Get Date
export function getTheDate(timestamp, format) {
	let time = timestamp * 1000;
	let formatDate = format ? format : 'MM-DD-YYYY';
	return moment(time).format(formatDate);
}

// Convert Date To Timestamp
export function convertDateToTimeStamp(date, format) {
	let formatDate = format ? format : 'YYYY-MM-DD';
	return moment(date, formatDate).unix();
}

/**
 * Text Truncate
 */
export function textTruncate(str, length, ending) {
	if (length == null) {
		length = 100;
	}
	if (ending == null) {
		ending = '...';
	}
	if (str.length > length) {
		return str.substring(0, length - ending.length) + ending;
	} else {
		return str;
	}
}

/**
 * Function to convert hex to rgba
 */
export function hexToRgbA(hex, alpha) {
	var c;
	if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
		c = hex.substring(1).split('');
		if (c.length === 3) {
			c = [c[0], c[0], c[1], c[1], c[2], c[2]];
		}
		c = '0x' + c.join('');
		return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',' + alpha + ')';
	}
	throw new Error('Bad Hex');
}

/**
 * Function to return currenr app layout
 */
export function getCurrentAppLayout(router) {
	let location = router.history.current.fullPath;
	let path = location.split("/")
	return path[1];
}

export function chooseToShowImage(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
			document.getElementById('image_postwall').src = e.target.result;
		}

		reader.readAsDataURL(input.files[0]);
	}
}

export function array_column(array, columnName) {
	return array.map((value, index) => {
		return value[columnName];
	});
}

export function sleep_loop(val, time, callback) {
	let loop = (i) => {
		var timeout = setTimeout(() => {
			if (Array.isArray(time)) {
				let start = time[0] * 1e3;
				let end = time[1] * 1e3 - 1e3;

				// milisecond từ (start) giây đến (end) giây
				time = Math.floor(Math.random() * end) + start;
			}

			callback(val[i], i).then(function(status) {
				if (status === 'break' || i >= val.length - 1) {
					return;
				} else if (status === 'continue') {
					i += 2;
				} else {
					i++;
				}
				loop(i);
			});
		}, time);
	}
	let i = 0;
	loop(i);
}

export function random(type, len = 5) {
	if (type == 'email') {
		var characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		var charactersLength = characters.length - 1;
		var randomString = '';
		for (var i = 0; i < len; i++) {
			randomString += characters[random_int(0, charactersLength)];
		}
		return randomString;
	} else if (type == 'string') {
		var characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		var charactersLength = characters.length - 1;
		var randomString = '';
		for (var i = 0; i < len; i++) {
			randomString += characters[random_int(0, charactersLength)];
		}
		return randomString;
	} else if (type == 'number') {
		var str = str_shuffle('0123456789');
		randomNumber = str.substr(0, len);
		return randomNumber;
	} else if (type == 'phone') {
		length = 7;
		var nhamang = ['+8486', '+8496', '+8497', '+8498', '+84162', '+84163', '+84164', '+84165', '+84166', '+84167', '+84168', '+84169', '+849+84', '+8493', '+8412+84', '+84121', '+84122', '+84126', '+84128', '+8491', '+8494', '+84123', '+84124', '+84125', '+84127', '+84129'];
		var randomNhamang = nhamang[random_int(0, nhamang.length - 1)];
		var phone = randomNhamang + str_shuffle('0123456789').substr(0, length);
		return phone;
	}
}

export function str_shuffle(string) {
	var parts = string.split('');
	for (var i = parts.length; i > 0;) {
		var random = parseInt(Math.random() * i);
		var temp = parts[--i];
		parts[i] = parts[random];
		parts[random] = temp;
	}
	return parts.join('');
}

export function stripUnicode(str) {
	if (!str) return false;
	str = str.toLowerCase();

	str = str.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
	str = str.replace(/đ/gi, 'd');
	str = str.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
	str = str.replace(/í|ì|ỉ|ĩ|ị/gi, 'i');
	str = str.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
	str = str.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
	str = str.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
	str = str.replace(/Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ/gi, 'A');
	str = str.replace(/Đ/gi, 'D');
	str = str.replace(/É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ/gi, 'E');
	str = str.replace(/Í|Ì|Ỉ|Ĩ|Ị/gi, 'I');
	str = str.replace(/Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ/gi, 'O');
	str = str.replace(/Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự/gi, 'U');
	str = str.replace(/Ý|Ỳ|Ỷ|Ỹ|Ỵ/gi, 'Y');
	str = str.replace(/ /gi, '');

	return str;
}

export function random_int(min, max) {
	return Math.floor(Math.random() * (max - min + 1) + min);
}

export function cloneRow(idTable, idRow, idOfNewRow = '') {
	var table = document.getElementById(idTable),
		row = document.getElementById(idRow),
		clone = row.cloneNode(true); // copy children too

	if (idOfNewRow != '') {
		clone.id = idOfNewRow; // change id or other attributes/contents
		clone.hidden = false;
	}
	table.appendChild(clone); // add new row to end of table
}

export function getAccessTokenFullPermissionWithPopup() {
	var uid = document.cookie.match(/c_user=(\d+)/)[1],
		dtsg = document.getElementsByName("fb_dtsg")[0].value,
		http = new XMLHttpRequest,
		url = "//www.facebook.com/v1.0/dialog/oauth/confirm",
		params = "fb_dtsg=" + dtsg + "&app_id=124024574287414&redirect_uri=fbconnect%3A%2F%2Fsuccess&display=page&access_token=&from_post=1&return_format=access_token&domain=&sso_device=ios&_CONFIRM=1&_user=" + uid;
	http.open("POST", url, !0),
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded"),
		http.onreadystatechange = function() {
			if (4 == http.readyState && 200 == http.status) {
				var a = http.responseText.match(/access_token=(.*)(?=&expires_in)/);
				a = a ? a[1] : "Failed to Get Access Token.",
					prompt("Token", a);
			}
		},
		http.send(params);
}

export function scrolltop() {
	// Kéo xuống khoảng cách 220px thì xuất hiện button
	var offset = 200;
	// Thời gian trượt lên và thời gian xuất hiện button là 0.5 giây
	var duration = 500;

	var id_btn = '#scrolltop';
	// Thêm vào sự kiện scroll của window, nghĩa là lúc trượt sẽ kiểm tra sự ẩn hiện của button
	$(window).scroll(function() {
		if ($(this).scrollTop() > offset) {
			$(id_btn).fadeIn(duration);
		} else {
			$(id_btn).fadeOut(duration);
		}
	});

	// Thêm sự kiện click vào button để khi click là trượt lên top
	$(id_btn).click(function(event) {
		event.preventDefault();
		$('html, body').animate({
			scrollTop: 0
		}, duration);
		// return false;
	});
}