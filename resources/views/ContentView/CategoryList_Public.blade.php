@include('header')

<div class="blog-list">
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr style="text-align: left;">
                <th>Title</th>
                <th>Description</th>
                <th>Last update date</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot hidden>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Last update date</th>
            </tr>
        </tfoot>
    </table>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script>
        $('a').removeClass('active');
        $('#catogory').addClass('active');

        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            } );
        
            // DataTable
            var table = $('#example').DataTable({
                initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        var that = this;
        
                        $( 'input', this.footer() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                that
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                    } );
                },
                bLengthChange: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: false,
                searchable: false,
            });
        });
    </script>
</div>

@include('footer')