$(document).ready(function () {

  // Sticky
  $(function () {
    $(window).scroll(function () {
      if ($(this).scrollTop() > 50) {
        $('.header').addClass("sticky");
      } else {
        $('.header').removeClass("sticky");
      }
    });
  });

  // Data Table
  window.datatable = $('.table-rank').DataTable(
    { dom: "" }
  );

  // Switch Stlye
  $('#viewTitles-list').click(function () {
    $('#listTitles').addClass('list');
    $('#viewTitles-grid').removeClass('active');
    $('#viewTitles-list').addClass('active')
  })
  $('#viewTitles-grid').click(function () {
    $('#listTitles').removeClass('list');
    $('#viewTitles-list').removeClass('active');
    $('#viewTitles-grid').addClass('active')
  })

  // navbar
  $('#navbarNavBtn').click(function () {
    $('#navbarNavBtnX').addClass('open');
    $('#navbarBackdrop').addClass('show');
  })
  $('#navbarNavBtnX').click(function () {
    $('#navbarNavBtnX').removeClass('open');
    $('#navbarBackdrop').removeClass('show');
  })

});
