<style>
    .cart_menu th{    }
</style>
<div class="modal-header alert alert-success">
    <h4 class="modal-title">Order Details (Reff. No: <?= $order['sale']['reference_no'];?>) </h4>
    <button type="button" class="close pull-right" style=" margin-top: -1em;" data-dismiss="modal">&times;</button>

</div>
<div class="modal-body ">
    <div class="row container">
        <div class="col-sm-6">
            <b class="modal-title">Customer Name : </b> <?= $order['sale']['customer'];?> 
        </div>
    <?php
        if(!empty($order['sale']['cf1']) || !empty($order['sale']['cf2'])) {
    ?>
        <div class="col-sm-6" >
            <div class="col-sm-6"><b>Patient Name : </b> <?= $order['sale']['cf1'];?></div>
            <div class="col-sm-6"><b>Doctor Name : </b> <?= $order['sale']['cf2'];?></div>
        </div>
    <?php }//end if. ?>
    </div>
    <div class="row ">                        
        <div class="col-sm-12 clearfix bling-div" style="background: #fff;width: 100%;">
            <div class="bill-to">
                <div class="form-outer">
                    <div class="cart-heading last">
                        <h4>Order Items <span class="pull-right" style="margin-right: 30px;">Date: <?= $order['sale']['date'];?></span></h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="table-outer">
                        <div class="table-responsive" ><br/>
                            <table class="table table-bordered ">
                                <thead>
                                    <tr class="cart_menu" style="border-top: 1px solid #ddd !important;">
                                        <th >Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th align="center">Tax</th>
                                        <th align="center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                  $orderitems = $order['items'];
                                  foreach ($orderitems as $key => $oitem) {
                                ?>
                                    <tr>
                                        <td class="product-image">
                                            <img src="<?= base_url('/assets/uploads/thumbs/'.$oitem->image);?>"  alt="<?= $oitem->product_name;?>" />
                                            <span class="cart_description"> 
                                                <h4><?= $oitem->product_name;?></h4><?php if($oitem->variant){echo '<sub>('.$oitem->variant.')</sub>';} ?></span>
                                        </td>
                                        <td class="text-right">
                                            <span><?= $Settings->symbol ?> <?= number_format($oitem->unit_price,2);?></span>
                                        </td>
                                        <td class="product-qty">
                                            <div class="cart_quantity_button text-center">
                                                <span><?= ($oitem->quantity);?></span> 
                                            </div> 
                                        </td>
                                        <td class="product-qty">
                                            <div class="cart_quantity_button text-right">
                                                <span><?= $Settings->symbol ?> <?= number_format($oitem->item_tax,2);?></span> 
                                            </div> 
                                        </td>
                                        <td class="product-total text-right">
                                            <span><?= $Settings->symbol ?> <?= number_format($oitem->subtotal,2);?></span>
                                        </td>                                                                                                  
                                    </tr>
                                <?php                                    
                                  }
                                ?>
                                    <tr class="total-count">                                     
                                        <td colspan="3"></td>
                                        <td>Sub Total:</td>
                                        <td class="tot-price"><?= $Settings->symbol ?> <?= number_format($order['sale']['total'],2)?></td>
                                    </tr>
                                    <tr class="total-count">                                     
                                        <td colspan="3"></td>
                                         <td>Item Tax:</td>
                                         <td class="tot-price"><?= $Settings->symbol ?> <?= number_format($order['sale']['product_tax'],2)?></td>
                                      </tr>
                                      <?php  if($order['sale']['order_tax'] > 0) { ?>
                                      <tr class="total-count">
                                          <td colspan="3"></td>
                                         <td>Order Tax:</td>
                                        <td class="tot-price"><?= $Settings->symbol ?> <?= number_format($order['sale']['order_tax'],2)?></td>
                                     </tr>
                                      <?php } else{ ?>
                                     <tr class="total-count" style="display: none;">
                                          <td colspan="3"></td>
                                     </tr>
                                      <?php } ?>
                                
                                       <?php  if($order['sale']['total_tax'] > 0) { ?>
                                    <tr class="total-count">                                     
                                        <td colspan="3"></td>
                                        <td>Total Tax:</td>
                                        <td class="tot-price"><?= $Settings->symbol ?> <?= number_format($order['sale']['total_tax'],2)?></td>
                                    </tr>
                                <?php } ?>
                                <?php if($order['sale']['shipping'] > 0) { ?>
                                    <tr class="total-count">                                     
                                        <td colspan="3"></td>
                                        <td>Shipping:</td>
                                        <td class="tot-price"><?= $Settings->symbol ?> <?= number_format($order['sale']['shipping'],2)?></td>
                                    </tr>
                                <?php } ?>
                                    <tr class="total-count">                                    
                                        <td colspan="3"></td>
                                        <td><b>Gross Total</b></td>
                                        <td class="tot-price"><b><?= $Settings->symbol ?> <?= number_format($order['sale']['grand_total'],2)?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>                                                                                    
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>			
    </div>
    <div class="row">
        <?php
        $billing_shipping = $order['billing_shipping'];
        $payments = $order['payment'];
        ?>
        <div class="col-sm-6 clearfix bling-div">
            <div class="bill-to">
                <div class="form-outer">
                    <div class="cart-heading">
                        <h4>Shipping Method</h4><br/>
                        <div class="clearfix"></div>
                    </div>
                    <div>
                      <?= $billing_shipping['shipping_method_name']?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 clearfix bling-div">
            <div class="bill-to">
                <div class="form-outer">
                    <div class="cart-heading">
                        <h4>Payment Information</h4>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 50%">
                                <b>Method: </b> <?php echo $paid_by = ($payments->paid_by == 'instomojo') ? 'Credit Card / Debit Card / Netbanking' : 'COD' ?>
                            </td> 
                            <td style="width: 50%">
                                <span class="pull-right"><b> Status:</b> <?= ucfirst($order['sale']['payment_status'])?></span>
                            </td>

                        </tr>
                    </table>    
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="bill-to col-sm-6">
            <div class="form-outer">
                <div class="cart-heading">
                    <h4>Billing Details</h4><br/>
                    <div class="clearfix"></div>
                </div>									 
                <div class="row" >
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <tr>
                                <td><i class="fa fa-user" aria-hidden="true" style="width:20px;float:left;padding: 3px 0 8px;"></i> <?= $billing_shipping['billing_name']?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-home" aria-hidden="true" style="width:20px;float:left;padding: 3px 0 8px;"></i> <?= $billing_shipping['billing_addr']?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-envelope" aria-hidden="true" style="width:20px;float:left;padding: 3px 0 8px;"></i> <?= $billing_shipping['billing_email']?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-phone" aria-hidden="true" style="width:20px;float:left;padding: 3px 0 8px;"></i> <?= $billing_shipping['billing_phone']?></td>
                            </tr>                                                 
                        </table>
                    </div>                                         
                </div> 
            </div> 
        </div>
        <div class="bill-to col-sm-6">
            <div class="form-outer">
                <div class="cart-heading">
                    <h4>Shipping Details</h4><br/>
                    <div class="clearfix"></div>
                </div>									 
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <tr>
                                <td><i class="fa fa-user" aria-hidden="true" style="width:20px;float:left;padding:3px 0 8px;"></i> <?= $billing_shipping['shipping_name']?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-home" aria-hidden="true" style="width:20px;float:left;padding:3px 0 8px;"></i> <?= $billing_shipping['shipping_addr']?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-envelope" aria-hidden="true" style="width:20px;float:left;padding:3px 0 8px;"></i> <?= $billing_shipping['shipping_email']?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-phone" aria-hidden="true" style="width:20px;float:left;padding:3px 0 8px;"></i> <?= $billing_shipping['shipping_phone']?></td>
                            </tr>                                                 
                        </table>
                    </div>                                         
                </div> 
            </div> 
        </div>
    </div>
</div>
<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
