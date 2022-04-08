/* ------------------------------------------------------------------------------
 *
 *  # Dashboard configuration
 *
 *  dashboard configuration. Contains charts and plugin initializations
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var Dashboard = function () {


    //
    // Setup module components
    //

    // Setup Switchery
    var _componentSwitchery = function() {
        if (typeof Switchery == 'undefined') {
            console.warn('Warning - switchery.min.js is not loaded.');
            return;
        }

        // Initialize multiple switches
        var switches = Array.prototype.slice.call(document.querySelectorAll('.form-input-switchery'));
        switches.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    };

    // Setup Daterangepicker
    var _componentDaterange = function() {
        if (!$().daterangepicker) {
            console.warn('Warning - daterangepicker.js is not loaded.');
            return;
        }

        // Initialize
        $('.daterange-ranges').daterangepicker(
            {
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2015',
                maxDate: '12/31/2019',
                dateLimit: { days: 60 },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: $('html').attr('dir') == 'rtl' ? 'right' : 'left',
                applyClass: 'btn-sm bg-slate-600 btn-block',
                cancelClass: 'btn-sm btn-light btn-block',
                locale: {
                    format: 'MM/DD/YYYY',
                    direction: $('html').attr('dir') == 'rtl' ? 'rtl' : 'ltr'
                }
            },
            function(start, end) {
                $('.daterange-ranges span').html(start.format('MMMM D') + ' - ' + end.format('MMMM D'));
            }
        );
        $('.daterange-ranges span').html(moment().subtract(29, 'days').format('MMMM D') + ' - ' + moment().format('MMMM D'));
    };

    // Use first letter as an icon
    var _componentIconLetter = function() {

        // Grab first letter and insert to the icon
        $('.table tr').each(function() {

            // Title
            var $title = $(this).find('.letter-icon-title'),
                letter = $title.eq(0).text().charAt(0).toUpperCase();

            // Icon
            var $icon = $(this).find('.letter-icon');
                $icon.eq(0).text(letter);
        });
    };




    //
    // Return objects assigned to module
    //

    return {
        initComponents: function() {
            _componentSwitchery();
            _componentDaterange();
            _componentIconLetter();
        },
        initCharts: function() {

        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    Dashboard.initComponents();
    Dashboard.initCharts();
});
