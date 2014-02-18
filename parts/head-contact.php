<?php // Settings
$spine_options = get_option( 'spine_options' );
if ( isset($spine_options['contact_name']) ) { $contact_name = $spine_options['contact_name']; } else { $contact_name = 'Washington State University'; }
if ( isset($spine_options['contact_department']) ) { $contact_department = $spine_options['contact_department']; } else { $contact_department = ''; }
if ( isset($spine_options['contact_url']) ) { $contact_url = $spine_options['contact_url']; } else { $contact_url = 'http://wsu.edu'; }
if ( isset($spine_options['contact_streetAddress']) ) { $contact_streetAddress = $spine_options['contact_streetAddress']; } else { $contact_streetAddress = 'PO Box 641227'; }
if ( isset($spine_options['contact_addressLocality']) ) { $contact_addressLocality = $spine_options['contact_addressLocality']; } else { $contact_addressLocality = 'Pullman, WA'; }
if ( isset($spine_options['contact_postalCode']) ) { $contact_postalCode = $spine_options['contact_postalCode']; } else { $spine_options['postalCode'] = '99164'; }
if ( isset($spine_options['contact_telephone']) ) { $contact_telephone = $spine_options['contact_telephone']; } else { $contact_telephone = '(509) 335-3564'; }
if ( isset($spine_options['contact_email']) ) { $contact_email = $spine_options['contact_email']; } else { $contact_email = 'info@wsu.edu'; }
if ( isset($spine_options['contact_ContactPoint']) ) { $contact_ContactPoint = $spine_options['contact_ContactPoint']; } else { $contact_ContactPoint = ''; }
if ( isset($spine_options['contact_ContactPointTitle']) ) { $contact_ContactPointTitle = $spine_options['contact_ContactPointTitle']; } else { $contact_ContactPointTitle = 'Contact Page...'; }
?>

<meta id="contact-details" itemscope itemtype="http://schema.org/Organization">
<meta itemprop="name" class="required" content="<?php echo $contact_name; ?>">
<meta itemprop="department" class="required" content="<?php echo $contact_department; ?>">
<meta itemprop="url" class="required" content="<?php echo $contact_url; ?>">
<meta itemprop="location" class="optional" content="<?php echo $contact_location; ?>">
<meta itemprop="streetAddress" class="optional" content="<?php echo $contact_streetAddress; ?>">
<meta itemprop="addressLocality" class="optional" content="<?php echo $contact_addressLocality; ?>">
<meta itemprop="postalCode" class="required" content="99164-4444">
<meta itemprop="telephone" class="required" content="<?php echo $contact_telephone; ?>">
<meta itemprop="email" class="required" content="<?php echo $contact_email; ?>">
<?php if ( isset($spine_options['contact_ContactPoint']) && $spine_options['contact_ContactPoint'] != ""  ) { ?>
	<meta itemprop="ContactPoint" title="<?php echo $contact_ContactPointTitle; ?>" class="optional" content="<?php echo $contact_ContactPoint; ?>">
<?php } ?>