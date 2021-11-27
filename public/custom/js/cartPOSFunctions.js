
"use strict";
var cartContent=null;
var orderContent=null;
var receiptPOS=null;
var cartTotal=null;
var ordersTotal=null;
var footerPages=null;
var total=null;
var expedition=null;
var modalPayment=null;

$('#localorder_phone').hide();
/**
 *
 * @param {Number} net The net value
 * @param {Number} delivery The delivery value
 * @param {String} expedition 1 - Delivery 2 - Pickup 3 - Dine in
 */
function updatePrices(net,delivery,expedition){
  var formatter = new Intl.NumberFormat(LOCALE, {
    style: 'currency',
    currency:  CASHIER_CURRENCY,
  });

  //totalPrice -- Subtotal
  //withDelivery -- Total with delivery

  //Subtotal
  cartTotal.totalPrice=net;
  cartTotal.totalPriceFormat=formatter.format(net);

  if(expedition==1){
    //Delivery
    cartTotal.delivery=true;
    cartTotal.deliveryPrice=delivery;
    cartTotal.deliveryPriceFormated=formatter.format(delivery);

    //Total
    cartTotal.withDelivery=net+delivery;
    cartTotal.withDeliveryFormat=formatter.format(net+delivery);//+"==>"+new Date().getTime();
    total.totalPrice=net+delivery;

     //modalPayment updated
    modalPayment.totalPrice=cartTotal.withDelivery;
    modalPayment.totalPriceFormat=cartTotal.withDeliveryFormat;
    modalPayment.received=0;


  }else{
    //No delivery
    //Delivery
    cartTotal.delivery=false;

    //Total
    cartTotal.withDelivery=net;
    cartTotal.withDeliveryFormat=formatter.format(net);
    total.totalPrice=net;

     //modalPayment updated
    modalPayment.totalPrice=net;
    modalPayment.totalPriceFormat=formatter.format(net);
    modalPayment.received=0;

  }
  total.lastChange=new Date().getTime();
  cartTotal.lastChange=new Date().getTime();

  cartTotal.expedition=1;

  console.log("Price update");

 
}

function updateSubTotalPrice(net,expedition){
  updatePrices(net,(cartTotal.deliveryPrice?cartTotal.deliveryPrice:0),expedition)
}

function addToCartVUE(){
  var addCartEndpoint='/cart-add';
  if(CURRENT_TABLE_ID!=null&&CURRENT_TABLE_ID!=undefined){
    addCartEndpoint+="?session_id="+CURRENT_TABLE_ID;
  }

    $("#itemsSelect").val([]);
    $('#itemsSelect').trigger('change');

    axios.post(addCartEndpoint, {
        id: $('#modalID').text(),
        quantity: $('#quantity').val(),
        extras:extrasSelected,
        variantID:variantID
      })
      .then(function (response) {
          if(response.data.status){
            $('#productModal').modal('hide');
            getCartContentAndTotalPrice();

            //$('#miniCart').addClass( "open" );
            openNav();
          }else{
            $('#productModal').modal('hide');
            js.notify(response.data.errMsg,"warning");
          }
      })
      .catch(function (error) {
        console.log(error);
      });
}

function getAllOrders(){
  axios.get('/poscloud/orders').then(function (response) {

    
    console.log("--- Orders --");
    console.log(response.data.orders);
    orderContent.items=response.data.orders;
    makeFree();
    response.data.orders.forEach(element => {
      makeOcccupied(element.id)
    });
    ordersTotal.totalOrders=response.data.count;
    //updateSubTotalPrice(response.data.total,true);
   })
   .catch(function (error) {
     console.log(error);
   });
}

function doMoveOrder(tableFrom,tableTo){
  console.log("Move from "+tableFrom+" to "+tableTo);
  axios.get('/poscloud/moveorder/'+tableFrom+'/'+tableTo).then(function (response) {
    if(response.data.status){
      js.notify(response.data.message, "success");
      getCartContentAndTotalPrice();
    }else{
      js.notify(response.data.message, "warning");
    }
    
    
  }).catch(function (error) {
    console.log(error);
    js.notify(error, "warning");
  });
}

function withSession(endpoint){
   if(CURRENT_TABLE_ID!=null&&CURRENT_TABLE_ID!=undefined){
    endpoint+="?session_id="+CURRENT_TABLE_ID;
   }
   return endpoint;
}
/**
 * getCartContentAndTotalPrice
 * This functions connect to laravel to get the current cart items and total price
 * Saves the values in vue
 */
function getCartContentAndTotalPrice(){

  //clear select item


   axios.get(withSession('/cart-getContent-POS')).then(function (response) {
    cartContent.items=response.data.data;

    //TODO - check if config is not empty
    var obj=response.data.config;
    console.log(obj)
    if( Object.keys(obj).length != 0 ){
      expedition.config=response.data.config;

      //Set the dd
      if(response.data.config.delivery_area){
        $("#delivery_area").val(response.data.config.delivery_area);
        $('#delivery_area').trigger('change');
        cartTotal.deliveryPrice=DELIVERY_AREAS[response.data.config.delivery_area];
      }
      if(response.data.config.timeslot){
        $("#timeslot").val(response.data.config.timeslot);
        $('#timeslot').trigger('change');
      }
    }
   
    
    updateSubTotalPrice(response.data.total,EXPEDITION);
   })
   .catch(function (error) {
     console.log(error);
   });

   //On the same call if POS, call get order
   if(IS_POS){
    getAllOrders();
   }
   
 };

function updateExpeditionPOS(){
  var dataToSubmit={
    table_id:CURRENT_TABLE_ID,
    client_name:$('#client_name').val(),
    client_phone:$('#client_phone').val(),
    timeslot:$('#timeslot').val(),
  };
  if(EXPEDITION==1){
    dataToSubmit.delivery_area=$('#delivery_area').val();
    dataToSubmit.client_address=$('#client_address').val();
  }
  console.log(dataToSubmit);
  axios.post(withSession('/poscloud/orderupdate'), dataToSubmit).then(function (response) {

    if(response.data.status){
      js.notify(response.data.message, "success");
    }else{
      js.notify(response.data.message, "warning");
    }
    
    
  }).catch(function (error) {
    console.log(error);
    js.notify(error, "warning");
  });

}

function submitOrderPOS(){
  
  $('#submitOrderPOS').hide();
  $('#indicator').show();
  var dataToSubmit={
    table_id:CURRENT_TABLE_ID,
    paymentType:$('#paymentType').val(),
    expedition:EXPEDITION,
  };
  if(EXPEDITION==1||EXPEDITION==2){
    //Pickup OR deliver
    dataToSubmit.custom={
      client_name:$('#client_name').val(),
      client_phone:$('#client_phone').val(),
    }
    dataToSubmit.phone=$('#client_phone').val();
    dataToSubmit.timeslot=$('#timeslot').val();
    if(EXPEDITION==1){
      dataToSubmit.addressID=$('#client_address').val();
      dataToSubmit.custom.deliveryFee=cartTotal.deliveryPrice;
    }
    
  }
  console.log(dataToSubmit);
  axios.post(withSession('/poscloud/order'), dataToSubmit).then(function (response) {
    console.log(response);
    $('#submitOrderPOS').show();
    $('#indicator').hide();

    $('#modalPayment').modal('hide');
    //Call to get the total price and items
    getCartContentAndTotalPrice();

    if(response.data.status){
      window.showOrders();
      js.notify(response.data.message, "success");
      receiptPOS.order=response.data.order;
      $('#modalPOSInvoice').modal('show');
    }else{
      js.notify(response.data.message, "warning");
    }
    
    
  }).catch(function (error) {
    console.log(error);
    $('#modalPayment').modal('hide');
    $('#submitOrderPOS').show();
    $('#indicator').hide();
    js.notify(error, "warning");
  });
}

/**
 * Removes product from cart, and calls getCartConent
 * @param {Number} product_id
 */
function removeProductIfFromCart(product_id){
    axios.post(withSession('/cart-remove'), {id:product_id}).then(function (response) {
      getCartContentAndTotalPrice();
      
    }).catch(function (error) {
      console.log(error);
    });
 }

 /**
 * Update the product quantity, and calls getCartConent
 * @param {Number} product_id
 */
function incCart(product_id){
  axios.get(withSession('/cartinc/'+product_id)).then(function (response) {
    getCartContentAndTotalPrice();
  }).catch(function (error) {
    console.log(error);
  });
}


function decCart(product_id){
  axios.get(withSession('/cartdec/'+product_id)).then(function (response) {
    getCartContentAndTotalPrice();
  }).catch(function (error) {
    console.log(error);
  });
}

//GET PAGES FOR FOOTER
function getPages(){
    axios.get('/footer-pages').then(function (response) {
      footerPages.pages=response.data.data;
    })
    .catch(function (error) {
      console.log(error);
    });

};

function dineTypeSwitch(mod){
  console.log("Change mod to "+mod);

  $('.tablepicker').hide();
  $('.takeaway_picker').hide();

  if(mod=="dinein"){
    $('.tablepicker').show();
    $('.takeaway_picker').hide();

    //phone
    $('#localorder_phone').hide();
  }

  if(mod=="takeaway"){
      $('.tablepicker').hide();
      $('.takeaway_picker').show();

    //phone
    $('#localorder_phone').show();
  }

}

function orderTypeSwither(mod){
      console.log("Change mod to "+mod);

      $('.delTime').hide();
      $('.picTime').hide();

      if(mod=="pickup"){
          updatePrices(cartTotal.totalPrice,null,false)
          $('.picTime').show();
          $('#addressBox').hide();
      }

      if(mod=="delivery"){
          $('.delTime').show();
          $('#addressBox').show();
          getCartContentAndTotalPrice();
      }
}

setTimeout(function(){
  if(typeof initialOrderType !== 'undefined'){
    console.log("Will change now to "+initialOrderType+" --");
    orderTypeSwither(initialOrderType);
  }else{
    console.log("No initialOrderType");
  }

},1000);

function chageDeliveryCost(deliveryCost){
  $("#deliveryCost").val(deliveryCost);
  updatePrices(cartTotal.totalPrice,deliveryCost,true);
  console.log("Done updatin delivery price");
}

 //First we beed to capture the event of chaning of the address
  function deliveryAddressSwithcer(){
    $("#addressID").change(function() {
      //The delivery cost
      var deliveryCost=$(this).find(':selected').data('cost');

      //We now need to pass this cost to some parrent funct for handling the delivery cost change
      chageDeliveryCost(deliveryCost);


    });

  }

  function deliveryTypeSwitcher(){
    $('.picTime').hide();
    $('input:radio[name="deliveryType"]').change(function() {
      orderTypeSwither($(this).val());
    })
  }

  function dineTypeSwitcher(){
    $('input:radio[name="dineType"]').change(function() {
      $('.delTimeTS').hide();
      $('.picTimeTS').show();
      dineTypeSwitch($(this).val());
    })
  }

  function paymentTypeSwitcher(){
    $('input:radio[name="paymentType"]').change(

      function(){
          //HIDE ALL
          $('#totalSubmitCOD').hide()
          $('#totalSubmitStripe').hide()
          $('#stripe-payment-form').hide()

          //One for all
          $('.payment_form_submiter').hide()
          

          if($(this).val()=="cod"){
              //SHOW COD
              $('#totalSubmitCOD').show();
          }else if($(this).val()=="stripe"){
              //SHOW STRIPE
              $('#totalSubmitStripe').show();
              $('#stripe-payment-form').show()
          }else{
            $('#'+$(this).val()+'-payment-form').show()
          }
      });
  }

  function deliveryAreaSwitcher(){
    $('#delivery_area').on('select2:select', function (e) {
      var data = e.params.data;
      console.log(data);
      updatePrices(cartTotal.totalPrice,DELIVERY_AREAS[data.id],1);
      //getCartContentAndTotalPrice();
      //cartTotal.deliveryPrice=1000;//DELIVERY_AREAS[data.id];
    });
    

    //cartTotal.deliveryPrice=DELIVERY_AREAS[response.data.config.delivery_area];
  }

window.onload = function () {

  console.log("Cart function called");

  //Expedition
  expedition=new Vue({
    el: '#expedition',
    data: {
      config:{}
    },
  })

  //VUE CART
  cartContent = new Vue({
    el: '#cartList',
    data: {
      items: [],
      config:{}
    },
    methods: {
      remove: function (product_id) {
        removeProductIfFromCart(product_id);
      },
      incQuantity: function (product_id){
        incCart(product_id)
      },
      decQuantity: function (product_id){
        decCart(product_id)
      },
    }
  })


  orderContent = new Vue({
    el: '#orderList',
    data: {
      items: [],
    },
    methods:
    {
      openDetails:function(id,receipt_number){
        console.log(id);
        window.openTable(id,"#"+receipt_number);
      }
    }
  })

  //GET PAGES FOR FOOTER
  getPages();

  //Payment Method switcher
  paymentTypeSwitcher();

  //Delivery type switcher
  deliveryTypeSwitcher();

  //For Dine in / takeout
  dineTypeSwitcher();

  //Activate address switcher
  deliveryAddressSwithcer();

  //Activate delivery area switcher
  deliveryAreaSwitcher();


  //VUE FOOTER PAGES
  footerPages = new Vue({
      el: '#footer-pages',
      data: {
        pages: []
      }
  })

  //VUE COMPLETE ORDER TOTAL PRICE
  total = new Vue({
    el: '#totalSubmit',
    data: {
      totalPrice:0
    }
  })


  //VUE TOTAL
  cartTotal= new Vue({
    el: '#totalPrices',
    data: {
      totalPrice:0,
      minimalOrder:0,
      totalPriceFormat:"",
      deliveryPriceFormated:"",
      delivery:true
    }
  })

  modalPayment= new Vue({
    el: '#modalPayment',
    data: {
      totalPrice:0,
      minimalOrder:0,
      totalPriceFormat:"",
      deliveryPriceFormated:"",
      delivery:true,
      valid:false,
      received:0
    }
  })


  receiptPOS=new Vue({
    el:"#modalPOSInvoice",
    data:{
      order:null
    },
    methods: {
      moment: function (date) {
        return moment(date);
      },
      formatPrice(price){
        var locale=LOCALE;
        if(CASHIER_CURRENCY.toUpperCase()=="USD"){
            locale=locale+"-US";
        }
    
        var formatter = new Intl.NumberFormat(locale, {
            style: 'currency',
            currency:  CASHIER_CURRENCY,
        });
    
        var formated=formatter.format(price);
    
        return formated;
      },
      date: function (date) {
        return moment(date).format('MMMM Do YYYY, h:mm:ss a');
      }
    },
  })
  

  //VUE TOTAL
  ordersTotal= new Vue({
    el: '#ordersCount',
    data: {
      totalOrders:0,
    }
  })

  //Call to get the total price and items
  getCartContentAndTotalPrice();

  var addToCart1 =  new Vue({
    el:'#addToCart1',
    methods: {
        addToCartAct() {

          addToCartVUE();
        },
    },
  });
}
