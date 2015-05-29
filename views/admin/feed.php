<ul>
    <?php if($count == 0): ?>
    <li>No Posts</li>
    <?php else: ?>
    <?php foreach($items as $item): ?>
    <li>
        <a class="<?php echo $pre; ?>-feed-link" href="<?php echo esc_url($item -> get_permalink()); ?>" target="_blank">
            <span class="<?php echo $pre; ?>-feed-title"><?php echo esc_html($item -> get_title()); ?></span>
            <span class="<?php echo $pre; ?>-feed-date"><?php echo $item -> get_date('j F Y g:i a'); ?></span>
        </a>
        <div class="<?php echo $pre; ?>-feed-content">
            <?php
            $content = strip_tags($item -> get_description());
            $content = substr(preg_replace('/\[.+\]/i', '', $content), 0, 256);

            if(empty($content)): ?>
            <b><i>No Content</i></b>
            <?php else: ?>
            <?php echo $content . '...'; ?>
            <?php endif; ?>
        </div>
    </li>
    <?php endforeach; endif; ?>
</ul>