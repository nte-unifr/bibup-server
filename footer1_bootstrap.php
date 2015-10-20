<div class="application-footer">
	&copy; BibUp - <a href="http://nte.unifr.ch">Centre DIT-NTE</a> - University of Fribourg - Switzerland
</div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="dist/js/bootstrap.min.js"></script>
<script src="dist/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
$(function() {
	//slidetoggle
	$('.toggler').click(function(){
		$(this).parents(".teamMemberRight").find('.toggleMe').slideToggle('fast');
		return false; //ensures no anchor jump
	});
});

$(document).ready(function() {
    $(".tablesorter").tablesorter({
        dateFormat : "ddmmyyyy",
        // pass the headers argument and assing a object
        headers: {
            0: { sorter: "shortDate" },
            // assign the secound column (we start counting zero)
            2: {
                // disable it by setting the property sorter to false
                sorter: false
            },
            // assign the third column (we start counting zero)
            3: {
                // disable it by setting the property sorter to false
                sorter: false
            },
            4: {
                // disable it by setting the property sorter to false
                sorter: false
            }
        },
        sortList: [[0,1]]
    });
});

</script>

</body>
</html>
