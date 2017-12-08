bootbox.setLocale("cs");

var App = {};

$(function () {
    $.nette.init();

    $('[data-ipub-forms-slug]').ipubFormsSlug();
    IPub.Forms.Slug.load();

    $('[data-toggle="tooltip"]').tooltip();
    
    $('input[data-type="date-picker"]').datetimepicker(
      {
        language: 'cs',  // en
        format: 'd. m. yyyy',  // mm/dd/yyyy
        minView: 'month',
        //startDate: '2016-09-01',
        //endDate: '2016-09-15',
        autoclose: true,
        todayBtn: true
      });
    
      $('input[data-type="datetime-picker').datetimepicker({
        language: 'cs',  // en
        format: 'd. m. yyyy hh:ii',  // mm/dd/yyyy hh:ii
        autoclose: true,
        todayBtn: true
      });

    $('[data-dependentselectbox]').dependentSelectBox();
});