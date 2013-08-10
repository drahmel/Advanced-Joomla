<?php if ($params->get('link_titles') && 
    !empty($this->item->readmore_link)) : ?>
<a href="<?php echo $this->item->readmore_link; ?>"> 
    <?php echo $this->escape($this->item->title); ?></a>
<a href="<?php echo $this->item->readmore_link; ?>" 
    target="_readmore" title="Open article in new window">
    <i class="icon-share-alt"></i>
</a>

<?php else : ?>
    <?php echo $this->escape($this->item->title); ?>
<?php endif; ?>

