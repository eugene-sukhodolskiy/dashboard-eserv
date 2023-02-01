const sysMonConf = {
	apiUrl: "http://192.168.1.100:61208/api/3"
};

const refreshMonitorData = (container, interval, failCallback) => {
	const cpuVal = container.find(".cpu .val");
	const ramVal = container.find(".ram .val");
	const diskVal = container.find(".disk .val");
	const uptimeVal = container.find(".uptime .val");

	$.getJSON(`${sysMonConf.apiUrl}/quicklook`, resp => {
		cpuVal.html(`${resp.cpu}%`);
		ramVal.html(`${resp.mem}%`);
		if(resp.cpu > 90) {
			cpuVal.removeClass("warning");
			cpuVal.addClass("danger");
		} else if(resp.cpu > 60) {
			cpuVal.removeClass("danger");
			cpuVal.addClass("warning");
		} else {
			cpuVal.removeClass("danger");
			cpuVal.removeClass("warning");
		}

		if(resp.mem > 90) {
			ramVal.removeClass("warning");
			ramVal.addClass("danger");
		} else if(resp.mem > 60) {
			ramVal.removeClass("danger");
			ramVal.addClass("warning");
		} else {
			ramVal.removeClass("danger");
			ramVal.removeClass("warning");
		}

	}).done(() => {
		setTimeout(() => {
			refreshMonitorData(container, interval, failCallback);
		}, interval)
	}).fail(() => {
		failCallback();
	});

	$.get(`${sysMonConf.apiUrl}/uptime`, resp => {
		const time = resp.substring(0, 5);
		uptimeVal.html(`${time}`);
	});

	$.get(`${sysMonConf.apiUrl}/fs`, resp => {
		for(let disk of resp) {
			if(disk.mnt_point == "/") {
				diskVal.html(`${disk.percent}%`);
				if(disk.percent > 90) {
					diskVal.removeClass("warning");
					diskVal.addClass("danger");
				} else if(disk.percent > 60) {
					diskVal.removeClass("danger");
					diskVal.addClass("warning");
				} else {
					diskVal.removeClass("danger");
					diskVal.removeClass("warning");
				}
				break;
			}
		}
	});
}

$(document).ready(() => {
	const container = $(".sys-monitor-short");
	refreshMonitorData(container, 3000, () => {
		container.addClass("err");
	});
});