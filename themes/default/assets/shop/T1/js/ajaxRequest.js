$(document).ready(function(){
	
     var baseUrl = $('#baseurl').val(); 
     
     $('#searchCategoryButton').click(function(){
       
        var key = $('#searchCategory').val();
        
        if(key.length <= 3) return false;
        
        var postData = 'keyword=' + key;
        var url = baseUrl + 'shop/searchCategories';
     
        $.ajax({
                type: 'get',
                url: url,
                data: postData,
                beforeSend: function(){ 
                    var alert = '<div class="overlay text-info"><i class="fa fa-refresh fa-spin"></i> Please Wait! Searching...</div>';
                    $("#searchCategoryList").html(alert);                    
                },
                success: function(dataList){
                    $("#searchCategoryList").html(dataList);
                },
                error: function(errormsg){
                    console.log(errormsg);
                }
            });  

    });
	
    
        
});

function searchProducts(){        
        
	var baseUrl = $('#baseurl').val(); 
	var catId   = $('#catId').val(); 
	var page    = $('#page').val(); 
	var limit   = $('#limit').val(); 
        var searchProducts   = $('#searchProducts').val();
        
        if(searchProducts.length < 4) {
            alert('Please search keyword should be minimum 4 charectors.');
            return false;
        }
        
	$('#catlog_products').html('<h4><i class="fa fa-refresh fa-spin text-danger" ></i> Please Wait ... </h4>');
        
	var postData = 'catId=' + catId;		 
            postData = postData + '&page=' + page;
            postData = postData + '&limit=' + limit;
            postData = postData + '&keyword=' + searchProducts;
           
	$.ajax({
                    type: "get",
                    url: baseUrl + 'shop/viewCatlogProducts',
                    data: postData,	
                    beforeSend: function() {
                        
                        $('#catlog_products').html('<h4><i class="fa fa-refresh fa-spin text-danger" ></i> Please Wait ... </h4>');
                    },
                    success: function( Data){ 

                        $('#catlog_products').html(Data);      
                    }
            });    
}

function loadProducts(){        
        
	var baseUrl = $('#baseurl').val(); 
	var catId   = $('#catId').val(); 
	var page    = $('#page').val(); 
	var limit   = $('#limit').val();          
        
	$('#catlog_products').html('<h4><i class="fa fa-refresh fa-spin text-danger" ></i> Please Wait ... </h4>');
        
	var postData = 'catId=' + catId;		 
            postData = postData + '&page=' + page;
            postData = postData + '&limit=' + limit;
         
	$.ajax({
                    type: "get",
                    url: baseUrl + 'shop/viewCatlogProducts',
                    data: postData,	
                    beforeSend: function() {
                        
                        $('#catlog_products').html('<h4><i class="fa fa-refresh fa-spin text-danger" ></i> Please Wait ... </h4>');
                    },
                    success: function( Data){ 

                        $('#catlog_products').html(Data);      
                    }
            });    
}

function loadCategoryProducts(catId){
    
    $('#catId').val(catId);
    $('#page').val(1);
    
    loadProducts();
}

function loadPageProducts(page){
    
    $('#page').val(page);
    
    loadProducts();
}

function loadSubCategory(catId){
        
    var resultId = '#subcategory_list_' + catId;
    var storageId = 'cate_'+ catId;
    
    if($(resultId).html()=='' ) {
         
        var baseUrl = $('#baseurl').val(); 
        
        var postData = 'parent_id=' + catId;
              
        $(resultId).html('<h4><i class="fa fa-refresh fa-spin text-danger" ></i> Please Wait ... </h4>');
        
        $.ajax({
                    type: "get",
                    url: baseUrl + 'shop/loadSubcategory',
                    data: postData,	
                    beforeSend: function() {
                        
                        $(resultId).html('<h4><i class="fa fa-refresh fa-spin text-success" ></i> Loading...</h4>');
                    },
                    success: function( Data ){ 

                        $(resultId).html(Data);      
                    }
            });
       
    }
}

function loadCategories(){
    
    var baseUrl = $('#baseurl').val();
    
    if (sessionStorage.category == '') {       
        getAllCategory(baseUrl);
    }
    
    if(sessionStorage.category) {

        var postData = 'categoryJson=' + sessionStorage.category;

        $.ajax({
                type: "post",
                url: baseUrl + 'shop/loadCategories',
                data: postData,	
                beforeSend: function() {

                    $('#myAccordion').html('<h4><i class="fa fa-refresh fa-spin text-danger" ></i> Loading...</h4>');
                },
                success: function( Data){ 

                    $('#myAccordion').html(Data);      
                }
        });
        
    } else {
        $('#myAccordion').html('<p class="text-red">Storage Data Not Found.</p>');
    }
}

function getAllCategory(baseUrl){ 

    $.ajax({
        type: "get",
        url: baseUrl + 'shop/allCategories',
        success: function( Data){
            // Storing Data
            sessionStorage.setItem('category', Data);     
        }
    });
    
}

function addToCart(prodId){
        
        var baseUrl = $('#baseurl').val(); 
        var postData = 'product_id=' + prodId;
        $('#cartNotify').modal('show');
        $('#bootstrapAlert').html('<div class="alert alert-info"><i class="fa fa-refresh fa-spin text-danger" ></i> Please Wait! Item is adding to cart</div>');
        $.ajax({
                type: "get",
                url: baseUrl + 'shop/addCartItems',
                data: postData,	
                success: function( Data){ 
                    $('#bootstrapAlert').html('<div class="alert alert-success"><i class="fa fa-check"></i> Item successfully added. Thank you.</div>');
                    
                    $('.cart-count').html(Data);
                    
                    setTimeout(function(){ $('#cartNotify').modal('hide'); }, 500);
                    
                }
        });
    
}

function updateCartCount(prodId , qty){
    
    var baseUrl = $('#baseurl').val(); 
    var postData = 'product_id=' + prodId;
        postData = postData + '&qty=' + qty;
        
    $.ajax({
                type: "get",
                url: baseUrl + 'shop/addCartItems',
                data: postData,	
                success: function( Data){ 
                  
                    $('.cart-count').html(Data);
                                        
                }
        });
}

/*
 * 
function updateCartCount(prodId , qty){
        
    var baseUrl = $('#baseurl').val(); 
    var postData = 'product_id=' + prodId;
        postData = postData + '&new_qty=' + qty;
       
    $.ajax({
            type: "get",
            url: baseUrl + 'shop/updateCartCount',
            data: postData,	
            success: function( Data){ 
               $('.cart-count').html(Data);                    
            }
    });
    
}
 */

function updateQtyCost(itemId){
    
    var qty = $('#qty_'+itemId).val();
    
    var tax = $('#item_tax_rate_'+itemId).val();
     
    var price = $('#item_price_'+itemId).val();
    
    var total = qty * price;
    var itemtax = ((total * tax) / 100); 
    
    $('#show_total_'+itemId).html(total.toFixed(2));
    $('#item_price_total_'+itemId).val(total.toFixed(2));
    
    $('#show_tax_total_'+itemId).html(itemtax.toFixed(2));
    $('#item_tax_total_'+itemId).val(itemtax.toFixed(2));
        
    calculateCart(); 
    
    updateCartCount(itemId , qty);
}

function calculateCart(){
   