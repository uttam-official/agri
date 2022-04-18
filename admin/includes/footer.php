</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?= BASE_URL ?>/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= BASE_URL ?>/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= BASE_URL ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="<?= BASE_URL ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- ChartJS -->
<script src="<?= BASE_URL ?>/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?= BASE_URL ?>/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?= BASE_URL ?>/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= BASE_URL ?>/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= BASE_URL ?>/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= BASE_URL ?>/plugins/moment/moment.min.js"></script>
<script src="<?= BASE_URL ?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= BASE_URL ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= BASE_URL ?>/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= BASE_URL ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= BASE_URL ?>/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= BASE_URL ?>/dist/js/main.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= BASE_URL ?>/dist/js/pages/dashboard.js"></script>
<script>
    // $(function() {
    //     var url = window.location;
    //     // for single sidebar menu
    //     $('ul.nav-sidebar a').filter(function() {
    //         return this.href == url;
    //     }).addClass('active');

    //     // for sidebar menu and treeview
    //     $('ul.nav-treeview a').filter(function() {
    //             return this.href == url;
    //         }).parentsUntil(".nav-sidebar > .nav-treeview")
    //         .css({
    //             'display': 'block'
    //         })
    //         .addClass('menu-open').prev('a')
    //         .addClass('active');
    // });
    /*** add active class and stay opened when selected ***/
    var url = window.location;
    var server=window.location.origin;

    // for sidebar menu entirely but not cover treeview
    $('ul.nav-sidebar a').filter(function() {
        if (this.href) {
            return this.href == url || url.href.indexOf(this.href) == 0;
        }
    }).addClass('active');
    //Dashboard
    window.location.href==`${server}/agri/admin/`?$('.dashboard').removeClass('bg-secondary').addClass('active'):$('.dashboard').removeClass('active').addClass('bg-secondary');

    // for the treeview
    $('ul.nav-treeview a').filter(function() {
        if (this.href) {
            return this.href == url || url.href.indexOf(this.href) == 0;
        }
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>
</body>

</html>