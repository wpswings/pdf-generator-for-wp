<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

if (! defined('ABSPATH')) exit;

class Elementor_Widget_WPS_PDF_Embed extends Widget_Base
{

	public function get_name()
	{
		return 'wps_pdf_embed';
	}

	public function get_title()
	{
		return __('WPSwings PDF Embed', 'pdf-generator-for-wp');
	}

	public function get_icon()
	{
		return 'eicon-file-download';
	}

	public function get_categories()
	{
		return ['wps_pdf_widgets']; // Or 'general'
	}

	protected function _register_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('PDF Settings', 'pdf-generator-for-wp'),
			]
		);

		$this->add_control(
			'pdf_url',
			[
				'label'       => __('PDF File', 'pdf-generator-for-wp'),
				'type'        => Controls_Manager::MEDIA,
				'media_type'  => 'application/pdf',
				'description' => __('Upload a PDF file to embed.', 'pdf-generator-for-wp'),
				'default'     => [
					'url' => 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$pdf_url = esc_url($settings['pdf_url']['url']);

		if ($pdf_url):
?>
			<iframe src="<?php echo $pdf_url; ?>" width="100%" height="500px" style="border: 1px solid #ddd;"></iframe>
<?php
		else:
			echo '<p>Please upload a valid PDF file.</p>';
		endif;
	}
}
