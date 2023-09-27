<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_deposit') . " (" . $company->name . ")"; ?>
                <br/> Balance : <?= $this->sma->formatMoney( $company->deposit_amount) ?>
            </h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("customers/add_deposit/" . $company->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
 
            <div class="row">
                <div class="col-sm-12">
                    <?php if ($Owner || $Admin) { ?>
                    <div class="form-group">
                        <?php echo lang('date', 'date'); ?>
                        <div class="controls">
                            <?php echo form_input('date', set_value('date', date($dateFormats['php_ldate'])), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group">
                        <input type="checkbox" name="services-check" id="servicescheck"  > <label for="servicescheck"> Add Service </label>    
                    </div>   
                    
                    <div class="form-group" id="serviceblock" style="display: none;">
                        <?php echo lang('Service', 'service_amount'); ?>
                        <div class="controls">
                            <select class="form-control" name="service_amount" id="service_amount">
                                <option value="">Select Service Amount </option>
                                <?php for($i=1; $i <= 10; $i++){ 
                                    $amt = 500 * $i;
                                    ?>
                                <option value="<?= $amt ?>"> <?= $amt ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group"id="cashblock" >
                        <?php echo lang('amount', 'amount'); ?>
                        <div class="controls">
                            <?php echo form_input('cash', set_value('amount'), 'class="form-control" id="amount1" '); ?>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <?php echo lang('Total', 'Total'); ?>
                        <div class="controls">
                            <?php echo form_input('amount', set_value('amount'), 'class="form-control" id="amount" required="required"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo lang('paid_by', 'paid_by'); ?>
                        <div class="controls">
                             <select name="paid_by" id="paid_by_1" class="form-control paid_by">
<?= $this->sma->paid_opts(); ?>
                                                    </select>
                            <?php //echo form_input('paid_by', set_value('paid_by'), 'class="form-control" id="paid_by"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo lang('note', 'note'); ?>
                        <div class="controls">
                            <?php echo form_textarea('note', set_value('note'), 'class="form-control" id="note"'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_deposit', lang('add_deposit'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/modal.js"></script>

<script>
  
    
       $('#servicescheck').on('ifChecked', function () {
          $('#serviceblock').show();        
          $('#cashblock').hide();
          $('#note').html('Services');
        });
        $('#servicescheck').on('ifUnchecked', function () {
             $('#serviceblock').hide();        
             $('#cashblock').show();
              $('#note').html('');
        });
        
        $('#service_amount').change(function(){
              var amt = $('#service_amount').val();
              $('#amount').val(amt);
             $('#amount1').val(0);
        });
        
        $('#amount1').change(function(){
            var amt =  $(this).val();
            var calper = parseFloat(amt) * parseFloat('<?= $Settings->deposit_discount ?>') / parseFloat(100);
         
             var total  = parseFloat(amt) + parseFloat(calper);
           $('#amount').val(total);
        });
</script>    