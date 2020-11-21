@include('header_decentralization')

<div class="blog-list">
    <button class="btn-add-new-blog"><a href="<?=url('/AddCategory');?>">Add new category</a></button>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr style="text-align: left;">
                <th>Title</th>
                <th>Description</th>
                <th>Last update date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($category_list as $item){
                echo "<tr>";
                    echo "<td user_id='".($item->id)."'>".($item->title)."</td>";
                    echo "<td>".($item->description)."</td>";
                    echo "<td>".($item->last_update_date)."</td>";
                echo "</tr>";
                
            }?>
        </tbody>
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

            /* Click on column Name */
            $(document).on("click", "#example tbody td:nth-child(1)", function(){
                var user_id = $(this).attr('user_id'),
                    url = <?="'".url('/EditCategory/?id=')."'";?> + user_id;

                location.href = url;
            });
        });

        /* Set active for menu */
        setActiveMenu('category_list');
    </script>
</div>

@include('footer')