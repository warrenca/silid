$(document).ready(function(){
  $('.datepicker').pickadate({
    onSet: function (ele) {
       if(ele.select){
              this.close();
       }
    }
  });
  $('.timepicker').pickatime({
    min: [7,30],
    max: [19,0],
    interval: 15,
    onSet: function (ele) {
      if(ele.select){
         this.close();
       }
    }
  });

  $('select').material_select();
});
