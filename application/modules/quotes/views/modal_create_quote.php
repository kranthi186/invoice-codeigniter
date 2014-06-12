<?php $this->layout->load_view('clients/jquery_client_lookup'); ?>

<script type="text/javascript">
	$(function()
	{
		$('.datepicker').datepicker( {autoclose: true, format: '<?php echo date_format_datepicker(); ?>'} );
		
		// Display the create quote modal
		$('#create-quote').modal('show');
        
        $('#create-quote').on('shown', function() {
            $("#client_name").focus();
        });
        
        $('#client_name').typeahead();
        
		// Creates the quote
		$('#quote_create_confirm').click(function()
		{
            console.log('clicked');
			// Posts the data to validate and create the quote; 
			// will create the new client if necessary
			$.post("<?php echo site_url('quotes/ajax/create'); ?>", { 
				client_name: $('#client_name').val(), 
				quote_date_created: $('#quote_date_created').val(),
				user_id: '<?php echo $this->session->userdata('user_id'); ?>',
				invoice_group_id: $('#invoice_group_id').val()
			},
			function(data) {
				var response = JSON.parse(data);
				if (response.success == '1')
				{
					// The validation was successful and quote was created
					window.location = "<?php echo site_url('quotes/view'); ?>/" + response.quote_id;
				}
				else
				{
					// The validation was not successful
					$('.control-group').removeClass('error');
					for (var key in response.validation_errors) {
						$('#' + key).parent().parent().addClass('error');
					}
				}
			});
		});
	});
	
</script>

<div id="create-quote" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2"
     role="dialog" aria-labelledby="modal_create_quote" aria-hidden="true">
	<form class="modal-content">
		<div class="modal-header">
			<a data-dismiss="modal" class="close">x</a>
			<h3><?php echo lang('create_quote'); ?></h3>
		</div>
		<div class="modal-body">
            <div class="form-group">
                <label for="client_name">
                    <?php echo lang('client'); ?>
                </label>
                <input type="text" name="client_name" id="client_name"
                       class="form-control" value="<?php echo $client_name; ?>"
                       autocomplete="off">
            </div>


			<div class="form-group has-feedback">
				<label for="quote_date_created">
                    <?php echo lang('quote_date'); ?>
                </label>

                <div class="date datepicker">
                    <input size="16" type="text" name="quote_date_created"
                           id="quote_date_created" class="form-control"
                           value="<?php echo date(date_format_setting()); ?>" readonly>
                    <span class="fa fa-calendar form-control-feedback"></span>
                </div>
			</div>
			
			<div class="form-group">
				<label for="invoice_group_id"><?php echo lang('invoice_group'); ?>: </label>
				<div class="controls">
					<select name="invoice_group_id" id="invoice_group_id"
                            class="form-control">
						<option value=""></option>
						<?php foreach ($invoice_groups as $invoice_group) { ?>
						<option value="<?php echo $invoice_group->invoice_group_id; ?>" <?php if ($this->mdl_settings->setting('default_quote_group') == $invoice_group->invoice_group_id) { ?>selected="selected"<?php } ?>><?php echo $invoice_group->invoice_group_name; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

		</div>

        <div class="modal-footer">
            <div class="btn-group">
                <button class="btn btn-danger" type="button" data-dismiss="modal">
                    <i class="fa fa-times"></i> <?php echo lang('cancel'); ?>
                </button>
                <button class="btn btn-success" id="quote_create_confirm" type="button">
                    <i class="fa fa-check"></i> <?php echo lang('submit'); ?>
                </button>
            </div>
        </div>

	</form>

</div>