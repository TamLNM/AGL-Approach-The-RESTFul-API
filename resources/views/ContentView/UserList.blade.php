@include('header_decentralization')

<div class="blog-list">
    <button class="btn-add-new-blog"><a href="<?=url('/AddNewUser');?>">Add new user</a></button>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr style="text-align: left;">
                <th>Name</th>
                <th>Email</th>
                <th>Roll</th>
                <th>Last update date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($user_list as $user){
                echo "<tr>";
                    echo "<td user_id='".($user->id)."'>".($user->name)."</td>";
                    echo "<td>".($user->email)."</td>";
                    echo "<td>".($user->role)."</td>";
                    echo "<td>".($user->last_update_date)."</td>";
                echo "</tr>";
                
            }?>
        </tbody>
        <tfoot hidden>
            <tr>
                <th>Name</th>
                <th>User</th>
                <th>Roll</th>
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
                $(this).html( '<input type="text" placeholder="Search ' + title + '" />' );
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
            $(document).on("click", "#example tbody td:nth-child(1) ", function(){
                var user_id = $(this).attr('user_id'),
                    url = <?="'".url('/EditUser/?id=')."'";?> + user_id;

                location.href = url;
            });

            /* Set active for menu */
            setActiveMenu('user_list');
        });
    </script>
</div>

@include('footer_decentralization')