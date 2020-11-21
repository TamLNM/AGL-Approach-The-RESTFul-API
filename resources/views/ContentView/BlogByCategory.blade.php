@include('header')

<style>
    #example_filter.dataTables_filter{
        margin-bottom: 15px;
    }
    .button-group{
        display: flex;
        padding: 5px;
        padding-left: 20px;
    }
    .search-blog-input{
        width: 150px;
        border: solid 1px #333!important;
        padding: 5px
    }
    .search-blog-button{
        width: 75px;
        border: solid 1px #333!important;
        background-color: #333;
        color: white;
        padding: 5px
    }
</style>

<form method="POST" action="<?=url('/SearchBlogByCategoryAndContent');?>">
    @csrf
    <div class="button-group">
        <input id="search-blog-input" type="text" class="search-blog-input" name="searchBlogInput" placeholder="">
        <button id="search-blog-button" class="search-blog-button" type="submit">Search</button>
    </div>
    <div class="blog-list">
        <div style="margin-bottom: 20px;">
            <label style="margin-right: 10px;">Categories *</label>
            <select id="category_id" name="category_id" class="select-content">
                <?php 
                    if ($category_id == 'All'){
                        echo "<option category_id='All' selected>All</option>";
                    }
                    else{
                        echo "<option category_id='All'>All</option>";
                    }
                        
                    foreach($category_list as $item){
                        if ($item->id == $category_id){
                            echo "<option selected category_id='".$item->id."'>".$item->title."</option>";
                        }
                        else{
                            echo "<option category_id='".$item->id."'>".$item->title."</option>";
                        }
                    }
                ?>
            </select>
        </div>      
        <table id="example" class="display" style="width:100%">
            <thead hidden>
                <tr style="text-align: left;">
                    <th>Image</th>
                    <th>Content</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($blog_list as $item){ ?>
                <tr>
                    <td style="width: 15%; text-align: center">
                        <img src="{{ URL::asset($item->url) }}" style="max-width: 150px;">
                    </td>
                    
                    <td>
                        <div>
                            <div style="display: flex; justify-content: space-between;">
                                <h2><?=$item->title;?></h2>
                                <!--<p style="padding-top: 5px;">10/16/2019 10:30:00 AM</p>-->
                                <p style="padding-top: 5px;"><?=$item->update_time;?></p>
                            </div>
                            <div style="font-size: 12px;"><?=$item->author;?></div>
                            <div><?=$item->content;?></div>
                            <div style="text-align: right; cursor: pointer; color: blue; text-decoration: underline;"><a href="<?=url('/BlogDetails?screen_name=blog_by_category&id='.$item->id);?>">More</a></div>
                        </div>
                    </td>                
                </tr>
                <?php } ?>
            </tbody>
            <tfoot hidden>
                <tr>
                    <th>Image</th>
                    <th>Content</th>
                </tr>
            </tfoot>
        </table>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() { 
        setActiveMenu('blog_by_category');
        setDataTable('examples');
        
        $('#category_id').on('change', function(){
            var url = <?="'".url('/BlogByCategory?category_id=')."'";?> + $('#category_id option:selected').attr('category_id');

            location.href = url;
        }); 
    });
</script>

@include('footer')