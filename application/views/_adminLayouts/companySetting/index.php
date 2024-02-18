<div class="page-info">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Apps</a></li>
			<li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
			<li class="breadcrumb-item active" aria-current="page">Pengaturan Koperasi</li>
		</ol>
	</nav>
</div>
<div class="main-wrapper">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>SAnksi dan Aturan Koperasi</h5>
				</div>
				<div class="card-body">
					<?= $this->session->flashdata('message') ?>

					<form action="<?= base_url('admin/setting/company/save')?>" method="POST" class="js_admin-setting-company">
						<input type="hidden" name="<?= $csrfName ?>" value="<?= $csrfToken ?>">
						<div class="col-md-12">
							<div class="form-group">
								<label for="setcom_document" class="form-label">Upload AD/ART</label><br>
								<input type="file" class="form-control js-image_upload" accept=".pdf" data-url-upload="<?= base_url('admin/AdminAjax/postSetcomDocument');?>" value="<?= set_value('setcom_document') ?: $dataSettingCompany->setcom_document ?>">
								<input type="hidden" name="setcom_document" id="setcom_document" value="<?= set_value('setcom_document') ?: $dataSettingCompany->setcom_document ?>">
								<small class="text-info">* Dokumen Saat ini : <?= $dataSettingCompany->setcom_document?></small>
								<small><?= form_error('setcom_document')?></small>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="js-ckeditor-minimal-toolbar">Aturan dan Sanksi Anggota</label> <br>
								<textarea name="setcom_rules" id="js-ckeditor-minimal-toolbar" data-kcfinder="<?= base_url('assets/kcfinder/browse.php');?>"><?= set_value('setcom_document') ?: $dataSettingCompany->setcom_rules ?></textarea>
								<small class="text-danger js_input-error-placement"><?= form_error('setcom_rules')?></small>
							</div>
						</div>
						<br/>
						<button type="button" class="btn btn-primary js-form_action_btn">Simpan</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
