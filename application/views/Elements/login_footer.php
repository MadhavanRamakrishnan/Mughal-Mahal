    <!-- JQuery v1.9.1 -->
	<script src="<?php echo base_url(); ?>/assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>/assets/js/bootstrap/bootstrap.min.js"></script>
    <!-- Custom JQuery -->
	<script src="<?php echo base_url(); ?>/assets/js/app/custom.js" type="text/javascript"></script>
	<script type="text/javascript">
		$("#showPassword").on('click', function(){
			var x = document.getElementById("password");
			if(x.type == "password")
			{
				x.type = "text";
				$("#showPassword i").addClass('fa-eye-slash').removeClass('fa-eye');
			}
			else 
			{
				$("#showPassword i").addClass('fa-eye').removeClass('fa-eye-slash');
				x.type = "password";	
			}
		});
	</script>
</body>
</html>
