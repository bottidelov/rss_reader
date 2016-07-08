        <div class="">
        <ul class="pagination"><!-- ページング -->
        <!-- 最初のページへ -->
            <?php if($page != 1):?>
            <li class="">
            	<a href="?p=1">|&laquo;</a>
            </li>
            <?php endif;?>
        <!-- 前へ -->
            <?php if($page != 1):?>
            <li class="">
            	<a href="?p=<?php echo ($page - 1);?>">前</a>
            </li>
            <?php endif;?>

        <!-- ページャーリンク -->
            <?php for($i=1; $i<=$numPages; $i++):?>
            <?php if($i == $page):?>
            	<li class="active"><a><?php echo $i;?></a>
            </li><!-- 現ページはリンクを設定せず別デザイン -->
            <?php else:?>

            <!-- 現行ページからインデックスの値を順に引いていって2以下はリンクを表示しない-->
            <!-- インデックスの値が現行ページ＋2以上のリンクは表示しない-->
            <?php if(!($page - $i > 2 or $i > $page + 2)):?>
            <li class=""><a href="?p=<?php echo $i;?>"><?php echo $i;?></a></li>
            <?php endif;?>

            <?php endif;?>
            <?php endfor;?>

        <!-- 次へ -->
            <?php if($page != $numPages):?><li class=""><a href="?p=<?php echo ($page + 1);?>">次</a></li><?php endif;?>
        <!-- 最終ページへ -->
            <?php if($page != $numPages):?><li class=""><a href="?p=<?php echo $numPages;?>">&raquo;|</a></li><?php endif;?>
        </ul>
        </div>
