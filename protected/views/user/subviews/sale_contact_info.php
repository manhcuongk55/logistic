<?php 
    $collaboratorGroup = $this->getUser()->collaborator_group;
    $sale = null;
    if(!$collaboratorGroup){
        return;
    }
    $sale = $collaboratorGroup->getCollaboratorOfType(Collaborator::TYPE_SALE);
    if(!$sale)
        return;
?>
<div>
    <h4>Nhóm CTV: <b><?php echo $collaboratorGroup->name ?></b></h4>
    <div>
        <div>
            Người đại diện: <b><?php echo $sale->name ?></b>
        </div>
        <div class="mg-t5">
            <i class="fa fa-skype"></i>
            <a href="skype:<?php echo $sale->skype ?>?chat" class="bold"><?php echo $sale->skype ?></a>
        </div>
        <div class="mg-t5">
            <i class="fa fa-phone"></i>
            <a href="tel:<?php echo $sale->phone ?>" class="bold"><?php echo $sale->phone ?></a>
        </div>
    </div>
</div>