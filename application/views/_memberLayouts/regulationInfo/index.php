<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Keanggotaan</a></li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $pageType == "aturan" ?  "Aturan Dan Sanksi Anggota" : "AD / ART Koperasi"?>
			</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>
						<?= $pageType == "aturan" ?  "Aturan Dan Sanksi Anggota" : "AD / ART Koperasi";?>
					</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message')?>
					<?php if ($pageType == "aturan") {

						echo $dataRegulation->setcom_rules;

					} else {?>
					<div>
						<iframe src="<?= base_url('assets/') ?>resources/documents/<?= $dataRegulation->setcom_document?>?page=hsn#toolbar=0" width="100%"></iframe>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
