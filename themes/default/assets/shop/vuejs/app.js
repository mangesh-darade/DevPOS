 
var app = new Vue({
    el:'#app',
    data:{
        info:'Welcome',
    },
    mounted(){
		getCartCount();
                getCartItems_();
	}
    
});

var filter = function(text, length, clamp){
    clamp = clamp || '...';
    var node = document.createElement('div');
    node.innerHTML = text;
    var content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};
Vue.filter('truncate', filter);


Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
});


/**
 * Product Image Url
 */
Vue.filter('imageurl',function(imagevalue){
    var img =baseurl+'/assets/uploads/'+imagevalue;
    return img; 
});

/**
 *  Product Price Format
 */
Vue.filter('priceformat', function(value){
    var val = (value/1).toFixed(2);
    return val.toString(); 
});


/**
 * Search Products
 */
Vue.component('autoComplete', {
      template: '<div><form action="'+baseurl+'shop/search" method="get"><input autocomplete="off" id="search" name="q" type="text" style="text-transform: capitalize;" placeholder="Search" v-model="search" v-on:keyup="getSearchData" class="form-control"><button class="searchbtn" type="submit"><i class="material-icons">search</i></button></form> <div class="panel-footer search-results" v-if="results.length"><ul class="list-group"><li class="list-group-item" v-for="result in results"  @click="selectoption(result.name)">{{ result.name|capitalize }}</li></ul></div></div>',
      data: function () {
        return {
          search: '',
          baseurl:baseurl,
          results: []
        }
      },
      methods: {
        getSearchData(){
        this.results = [];
        if(this.search.length > 0){
         axios.get(baseurl+'eshop_api/suggestions',{params: {search: this.search}}).then(response => {
          this.results = response.data;
          
         });
        }
       },
       selectoption:function(option){
           this.search = option;
           this.results = []
       }
       
      },
    });


/**
 * Header
 * Search Products
 */
new Vue({
     el:'#headersearch',
    data: function () {
        return {
          search: '',
          baseurl:baseurl,
          results: []
        }
      },
      methods: {
        getSearchData(){
        this.results = [];
        if(this.search.length > 0){
         axios.get(baseurl+'eshop_api/suggestions',{params: {search: this.search}}).then(response => {
          this.results = response.data;
          $('.search-results').show();
         });
        }
       },
       selectoption:function(option){
           this.search = option;
           this.results = []
       }
       
      },
    });


/**
 *  Sidebar Cateory
 */

new Vue({
  el:'#nav-mobile-category',
  data:{
	  sidebar_categories: undefined,
	  title:'Sidebar Categories',
	  subcategories_show:false,
	  isActive: false,
	  baseurl: baseurl,
  },
   mounted() {
    this.getList();
	
  },
  methods:{
	  getList:function(){
		axios
		  .get(baseurl+"eshop_api/get_categories")
		  .then(res => {
			this.sidebar_categories = res.data
			//console.log(res.data)
		  });
	  },
	 getCategoryImage:function(image){
		 if(image==null || image=='')
            var img =baseurl+'assets/uploads/no_image.png';
		else
			var img =baseurl+'assets/uploads/thumbs/'+image;
            return img;
    },
	openSubcategory:function(CatId){
		this.isActive = !this.isActive;
		$('.MainCat_'+CatId).toggle();
		$('.ChildCat_'+CatId)=this.isActive;
		console.log(CatId)
	},	
  }
});

/**
 * Photo upload
 */
new Vue({
  el:'#modal1photo',
 
    data() {
        return {
            file: '',
            baseurl:baseurl,
        }
    },
    methods: {
      submitForm(){
                      loader('show');

            let formData = new FormData();
            formData.append('file', this.file);
  
            axios.post(baseurl+'shop/changeprofile',
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }
            ).then(function(data){
                if(data.data.status =='success'){
                     $("#profileimage").attr("src", data.data.imageurl);
                     $('.modal').hide();
                 } else {
                     $('#imagemag').html(data.data.message);
                 }
             loader();

            })
            .catch(function(){
              console.log('FAILURE!!');
            });
                       

      },
  
      onChangeFileUpload(){
        this.file = this.$refs.file.files[0];
      }


    }
   
});



/**
 * Order View
 */
/*new Vue({
       el:"#odermodel",
       data: function () {
        return {
          orderdata: '<h1> Test </h1>',
          baseurl:baseurl,
          results: []
        }
      },

       methods:{
           getorderDetails(transaction_key, userid ,status){
               var postData = 'transaction_key=' + transaction_key + '&user_id=' + userid + '&status=' + status;
                axios.get(baseurl+'/shop/orderDetails?'+postData).then(response => {
                this.orderdata = response.data;

               });
           }
       }
    });
*/

/**
 *  Add to cart
 */

function getCartItems_(){
	var slitems_str = localStorage.getItem('slitems');
	//console.log(slitems_str);
	slitems = {};
	CartCount = 0;
	$.each( JSON.parse(slitems_str), function( key, value ) {
		slitems[key] = value;
		localStorage.setItem('slitems', JSON.stringify(slitems));
		CartCount = parseFloat(CartCount) + parseFloat(value.CartItemQty);
	});
	$('.cart-item-count').text(CartCount);
}
function getCartCount(){
	var slitems_str = localStorage.getItem('slitems');
	CartCount = 0;
	$.each( JSON.parse(slitems_str), function( key, value ) {
		CartCount = parseFloat(CartCount) + parseFloat(value.CartItemQty);
	});
	$('.cart-item-count').text(CartCount);
}
function addToCart_(ProductId, ProductItemId, ManualUpdate=''){
	var slitems_cart = localStorage.getItem('slitems_cart');
	//var ProductItemId = $('.ProductItemId_'+ProductId).val();
	var ProductItemQty = 0;
	if(slitems_cart==null){
		slitems = {};
		ProductItemQty = $('.ProductItemQty_'+ProductId).val();
	}else{
		ProductItemQty = $('.ProductItemQty_'+ProductId).val();
		if(ManualUpdate==''){
			var slitems_str = localStorage.getItem('slitems');
			$.each( JSON.parse(slitems_str), function( key, value ) {
				if(key==ProductItemId){
					ProductItemQty = value.CartItemQty;
					if(parseFloat(value.CartItemQty)<parseFloat(value.stocks)){
						ProductItemQty = parseFloat(value.CartItemQty)+parseFloat(1);
					}
				}
				
			});
		}
	}
	//alert(baseurl+"eshop_api/addToCart/"+ProductItemId+'/'+ProductItemQty);
	$.ajax({
		type: "get", async: false,
		url: baseurl+"eshop_api/addToCart/"+ProductItemId+'/'+ProductItemQty,
		dataType: "json",
		success: function (res) {
			slitems[ProductItemId] = res;
			localStorage.setItem('slitems', JSON.stringify(slitems));
			localStorage.setItem('slitems_cart', 1);
			$('.cart-success-msg').fadeIn('slow');
			$('.cart-success-msg').delay(5000).fadeOut('slow');
			//console.log(localStorage.getItem('slitems'));
			getCartCount();
			loader();
		}
	});
}
function loader(action=''){
	if(action=='show')
		$('.loader').fadeIn('slow');
	else
		$('.loader').delay(1000).fadeOut('slow');
}
function updateCartQty(Qty, QtyId){
        var QtyInputUpdate = $('#QtyInputUpdate').val();
	if(QtyInputUpdate==0)
		return;
	var QtyIdSplit = QtyId.split("-");
	var ProductItemId = QtyIdSplit[1];
	var slitems_cart = localStorage.getItem('slitems');
	var clitems = {};
	var total_amount = 0;
	$.each( JSON.parse(slitems_cart), function( key, value ) {
		if(key==ProductItemId){
			value.CartItemQty=Qty;
		}
		clitems[key] = value;
		localStorage.setItem('slitems', JSON.stringify(clitems));
		total_amount = parseFloat(total_amount) + parseFloat(value.product_price*value.CartItemQty);
	});
	$('.total_amount').text(total_amount);
	//console.log(localStorage.getItem('slitems'));
	//console.log('hello '+Qty+' '+ProductItemId);
}



/** Add Wishlist Product **/
f