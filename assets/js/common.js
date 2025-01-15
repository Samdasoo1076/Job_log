	$(document).ready(function() {

		$("#confirm_passwd").keyup(function(e)  {
			if(e.keyCode == 13) {
				$("#btn_confirm_passwd").trigger("click");
			}
		});

	});
	
	function print_go(){
		$(".contentsarea").printThis();
	}


	function js_addr_page(nPage) {

		var mode = "FRONT_SEARCH_ADDR";
		var nPage = nPage;

		var request = $.ajax({
			url:"/_common/ajax_common_dml.php",
			type:"POST",
			data:{mode:mode, addr_name:$("#search_addr_name").val().trim(), nPage:nPage},
			dataType:"json"
		});

		request.done(function(data) {

			if (parseInt(data.total) == 0) { 
				$("#ul_search_addr_list").html("<dl><dt><strong>검색된 자료가 없습니다.</strong></dt></dl>");
			} else { 
				$("#ul_search_addr_list").html(data.list);
			}

			$("#page_navi_search_addr").html(data.page);
		
		});

	}