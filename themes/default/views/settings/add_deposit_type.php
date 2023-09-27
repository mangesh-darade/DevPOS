<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Add_Deposit_Type'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/add_deposit_type", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?= lang('Deposit_type *', 'Deposit_type'); ?>
                <?= form_input('dp_type', set_value('dp_type'), 'class="form-control" id="dp_type" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang('Default_Deposit_Amt *', 'default_deposit_amt'); ?>
                <?= form_input('default_deposit_amt', set_value('default_deposit_amt'), 'class="form-control" id="default_deposit_amt" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang('Supercash_Min_Amt *', 'supercash_min_amt'); ?>
                <?= form_input('supercash_min_amt', set_value('supercash_min_amt'), 'class="form-control" id="supercash_min_amt" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang("Supercash (Enable/Disable)", "supercash") ?>
                <?php
                $cat[''] = lang('select').' '.lang('Option');
                // foreach ($categories as $pcat) {
                    $cat['Y'] = "Yes";
                    $cat['N'] = "No";
                // }
                echo form_dropdown('supercash', $cat,'','class="form-control select" id="supercash" style="width:100%" required="required"')
                ?>
            </div>
            
            <div class="form-group">
                <?= lang('Supercash_Percentage *', 'supercash_percentage'); ?>
                <?= form_input('supercash_percentage', set_value('supercash_percentage'), 'class="form-control" id="supercash_percentage" required="required"'); ?>
            </div>
            
            <div class="form-group">
                <?= lang("Refundable (Enable/Disable)", "refundable") ?>
                <?php
                $cat[''] = lang('select').' '.lang('Option');
                // foreach ($categories as $pcat) {
                    $cat['Y'] = "Yes";
                    $cat['N'] = "No";
                // }
                echo form_dropdown('refundable', $cat,'','class="form-control select" id="refundable" style="width:100%" required="required"')
                ?>
            </div>
            <div class="form-group">
                <?= lang('Start_Date *', 'Start_Date'); ?>
                <?= form_input('start_date', set_value('start_date'), 'class="form-control" id="start_date" required="required"'); ?>
            </div>
            <div class="form-group">
                <?= lang('End_Date *', 'End_Date'); ?>
                <?= form_input('end_date', set_value('end_date'), 'class="form-control" id="end_date" required="required"'); ?>
            </div>
            <!-- <div class="col-sm-4"> -->
                            <!-- <div class="form-group choose-date hidden-xs">
                                <div class="controls">
                                    <?= lang("date_range", "date_range"); ?>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text"
                                            value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'].'-'.$_POST['end_date'] : "";?>"
                                            id="daterange_new" class="form-control">
                                        <span class="input-group-addon" style="display:none;"><i
                                                class="fa fa-chevron-down"></i></span>
                                        <input type="hidden" name="start_date" id="start_date"
                                            value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : "";?>">
                                        <input type="hidden" name="end_date" id="end_date"
                                            value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : "";?>">
                                    </div>
                                </div>
                            </div> -->
                        <!-- </div> -->
            <div class="form-group">
                <?= lang('Lock_in_Days *', 'lock_in_days'); ?>
                <?= form_input('lock_in_days', set_value('lock_in_days'), 'class="form-control" id="lock_in_days"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_deposit_type', lang('Add_Deposit_Type'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<?= $modal_js ?>