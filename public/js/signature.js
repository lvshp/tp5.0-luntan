function submitSign() {
	var htmlobj = $.ajax({
		type: 'POST',
		url: "/?s=setsignature",
		data: {
			content: $("#signature-box").val()
		},
		async:true,
		error: function() {
			alert("错误：" + htmlobj.responseText);
			return;
		},
		success: function() {
			$("#signature").html(htmlobj.responseText);
			$("#signature-box").css({"display":"none"});
			$("#signature").css({"display":"block"});
			return;
		}
	});
}
function editsignature() {
	$("#signature").css({"display":"none"});
	$("#signature-box").css({"display":"block"});
}