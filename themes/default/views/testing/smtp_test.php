<div class="container">
    <h1 class="text-center"> SMTP Mail Send </h1>
     <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("testing", $attrib);
            ?>

        <div class="row form-group">
            <div class="col-sm-3">
                <label>Protocol :</label>  
            </div>
            <div class="col-sm-6">
                <select class="form-control" name="protocol">
                    <option value="smtp">SMTP</option>
                    <option value="sendmail">Sendmail</option>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-3">
                <label>SMTP HOST :</label>  
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="smtp_host" value="ssl://smtp.gmail.com" />
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-3">
                <label>SMTP User :</label>  
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="smtp_user" value="simplyposmailtest" />
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-3">
                <label>SMTP Password :</label>  
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="smtp_pass" value="Vipin@554" />
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-3">
                <label>SMTP Port:</label>  
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="smtp_port" value="465" />
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-3">
                <label>SMTP Crypto :</label>  
            </div>
            <div class="col-sm-6">
                <select class="form-control" name="smtp_crypto">
                    <option value="">None</option>
                    <option value="tls" selected="selected">TLS</option>
                    <option value="ssl">SSL</option>
                </select>    
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-3">
                <label>From :</label>  
            </div>
            <div class="col-sm-6">
                <input type="email" class="form-control" name="from" value="simplyposmailtest@gmail.com" />
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-3">
                <label>From Name :</label>  
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="name" value="SimplySafe POS" />
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-3">
                <label>To :</label>  
            </div>
            <div class="col-sm-6">
                <input type="email" class="form-control" name="to" value="" />
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-3">
                <label>Subject :</label>  
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="subject" value="SMTP Mail Send Testing" />
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-3">
                <label>Message :</label>  
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="message" value="SMTP Mail Send Testing Message" >
            </div>
        </div>

       <div class="row form-group">
            <div class="col-sm-3">
                <label>Attachment :</label>  
            </div>
            <div class="col-sm-6">
                <input type="file" class="form-control" name="attachment"  >
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </div>
   <?php echo form_close(); ?>
</div>    

