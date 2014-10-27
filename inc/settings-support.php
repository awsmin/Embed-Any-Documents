 <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e('Name', 'ead'); ?></th>
        <td>
          <input type="text" name="site_name" value="" />
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Email ID', 'ead'); ?></th>
        <td>
           <input type="text" name="email_id" value="" />
        </td>
        </tr>
        <tr valign="top">
           <th scope="row"><?php _e('Problem', 'ead'); ?></th> 
           <td>
           	<textarea name="problem"> </textarea>
        </td>
        </tr>
        <tr valign="top">
        <td>
        <p class="submit">
        	<input type="submit" name="submit" id="submit" class="button button-primary" value="Submit">
        </p>
    	</td>
    	</tr>
</table>
<script type="text/javascript">
$.ajax({
	type: "POST",
	url:"<?php echo get_option('home')?>/wp-admin/admin-ajax.php",
	data: {  action: 'contactform' , contact :   $("#conform").serialize()},
	success: function(data)
	{
 
	}
});
</script>