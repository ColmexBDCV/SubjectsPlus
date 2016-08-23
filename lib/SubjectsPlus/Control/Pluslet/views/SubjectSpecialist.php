<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 8/17/16
 * Time: 4:29 PM
 */
?>


<div id="subject-specialist-content" data-pluslet-id="<?php echo $this->_pluslet_id; ?>">
<?php
    $settings = array();
    foreach($this->_editors as $staff):
        $settings = $this->getSubjectSpeicalistSettings($staff, $this->_array_keys, $this->_data_array);
        $this->setSubjectSpecialist($settings);
?>

<div class="subject-specialists" data-staff-id="<?php echo $this->staff_id; ?>">
    <h4 data-show-name="<?php echo $this->showName; ?>"><?php echo $this->fullname; ?></h4>
    <div data-show-photo="<?php echo $this->showPhoto; ?>" ><img src="<?php echo $this->img_url; ?>" /> </div>

    <ul class="staff-details">
        <li class="staff-content" data-show-title="<?php echo $this->showTitle; ?>"><?php echo $this->title; ?></li>
        <li class="staff-content" data-show-email="<?php echo $this->showEmail; ?>"><?php echo $this->email; ?></li>
        <li class="staff-content" data-show-phone="<?php echo $this->showPhone; ?>">(<?php echo $this->tel_prefix; ?>) <?php echo $this->tel; ?></li>
    </ul>
    <div class="social-details">
        <div class="staff-social" data-show-facebook="<?php echo $this->showFacebook; ?>">
            <a href="https://facebook.com/<?php echo $this->facebook; ?>"><i class="fa fa-facebook"></i></a>
        </div>
        <div class="staff-social" data-show-twitter="<?php echo $this->showTwitter ; ?>">
            <a href="https://twitter.com/<?php echo $this->twitter; ?>"><i class="fa fa-twitter"></i></a>
        </div>
        <div class="staff-social" data-show-pinterest="<?php echo $this->showPinterest; ?>">
            <a href="https://pinterest.com/<?php echo $this->pinterest; ?>"><i class="fa fa-pinterest"></i></a>
        </div>
        <div class="staff-social" data-show-instagram="<?php echo $this->showInstagram; ?>">
            <a href="https://instagram.com/<?php echo $this->instagram; ?>"><i class="fa fa-instagram"></i></a>
        </div>
    </div>
</div>

<?php endforeach; ?>

</div>

