<?php 
    $hobbies_and_interest = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'hobbies_and_interest');
    $hobbies_and_interest_data = json_decode($hobbies_and_interest, true);
?>
<div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
    <div id="info_hobbies_and_interest">
        <div class="card-inner-title-wrapper pt-0">
            <h3 class="card-inner-title pull-left">
                <?php echo translate('hobbies_and_interests')?>
            </h3>
            <div class="pull-right">
                <button type="button" id="unhide_hobbies_and_interest" <?php if ($privacy_status_data[0]['hobbies_and_interest'] == 'yes') {?> style="display: none" <?php }?> class="btn btn-base-1 btn-sm btn-icon-only btn-shadow mb-1" onclick="unhide_section('hobbies_and_interest')">
                <i class="fa fa-unlock"></i> <?=translate('show')?>
                </button>
                <button type="button" id="hide_hobbies_and_interest" <?php if ($privacy_status_data[0]['hobbies_and_interest'] == 'no') {?> style="display: none" <?php }?> class="btn btn-dark btn-sm btn-icon-only btn-shadow mb-1" onclick="hide_section('hobbies_and_interest')">
                <i class="fa fa-lock"></i> <?=translate('hide')?>
                </button>
                <button type="button" class="btn btn-base-1 btn-sm btn-icon-only btn-shadow mb-1" onclick="edit_section('hobbies_and_interest')">
                <i class="ion-edit"></i>
                </button> 
            </div>
        </div>
        <div class="table-full-width">
            <div class="table-full-width">
                <table class="table table-profile table-responsive table-striped table-bordered table-slick">
                    <tbody>
                        <tr>
                            <td class="td-label">
                                <span><?php echo translate('hobby')?></span>
                            </td>
                            <td>
                                <?=$hobbies_and_interest_data[0]['hobby']?>
                            </td>
                            </tr>
                            <tr>
                            <td class="td-label">
                                <span><?php echo translate('interest')?></span>
                            </td>
                            <td>
                                <?=$hobbies_and_interest_data[0]['interest']?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="edit_hobbies_and_interest" style="display: none">
        <div class="card-inner-title-wrapper pt-0">
            <h3 class="card-inner-title pull-left">
                <?php echo translate('hobbies_and_interests')?>
            </h3>
            <div class="pull-right">
                <button type="button" class="btn btn-success btn-sm btn-icon-only btn-shadow" onclick="save_section('hobbies_and_interest')"><i class="ion-checkmark"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-icon-only btn-shadow" onclick="load_section('hobbies_and_interest')"><i class="ion-close"></i></button>
            </div>
        </div>
        
        <div class='clearfix'></div>
        <form id="form_hobbies_and_interest" class="form-default" role="form">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group has-feedback">
                        <label for="hobby" class="text-uppercase c-gray-light"><?php echo translate('hobbies')?></label>
                        <textArea class="form-control no-resize" name="hobby"><?=$hobbies_and_interest_data[0]['hobby']?></textArea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group has-feedback">
                        <label for="interest" class="text-uppercase c-gray-light"><?php echo translate('interests')?></label>
                        <textArea class="form-control no-resize" name="interest"><?=$hobbies_and_interest_data[0]['interest']?></textArea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>