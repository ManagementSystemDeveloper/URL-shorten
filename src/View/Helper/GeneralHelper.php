<?php
namespace App\View\Helper;

use Cake\View\Helper;

/**
 * @property \Cake\View\Helper\FormHelper $Form
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class GeneralHelper extends Helper
{
    public $helpers = ['Form', 'Html', 'Url'];

    public function buildMoreFields($key = 0, $smart = null)
    {
        if (is_null($smart)) {
            $smart = [
                'country' => '',
                'os' => '',
                'device_type' => '',
                'model' => '',
                'url' => ''
            ];
        }
        ob_start();
        ?>
        <div class="genius_fields">
            <table>
                <tr>
                    <td>
                        <div class="form-inline">
                            <?=
                            $this->Form->control('smart[' . $key . '][country]', [
                                'label' => false,
                                'options' => get_countries(),
                                'empty' => __('Any Country'),
                                'class' => 'form-control input-sm',
                                'value' => $smart['country']
                            ]);
                            ?>
                            <i class="fa fa-plus hidden-xs hidden-sm" aria-hidden="true"></i>
                            <?=
                            // $dd->getOs('name') OR $dd->getOs('short_name')
                            $this->Form->control('smart[' . $key . '][os]', [
                                'label' => false,
                                'options' => get_operating_systems(),
                                'empty' => __('Any Operating System'),
                                'class' => 'form-control input-sm',
                                'value' => $smart['os']
                            ]);
                            ?>
                            <i class="fa fa-plus hidden-xs hidden-sm" aria-hidden="true"></i>
                            <?=
                            // $dd->getDeviceName()
                            $this->Form->control('smart[' . $key . '][device_type]', [
                                'label' => false,
                                'options' => get_device_types(),
                                'empty' => __('Any Device Type'),
                                'class' => 'form-control input-sm',
                                'value' => $smart['device_type']
                            ]);
                            ?>
                            <i class="fa fa-plus hidden-xs hidden-sm" aria-hidden="true"></i>
                            <?=
                            // $dd->getModel()
                            $this->Form->control('smart[' . $key . '][model]', [
                                'label' => false,
                                'options' => get_models(),
                                'empty' => __('Any Model'),
                                'class' => 'form-control input-sm',
                                'value' => $smart['model']
                            ]);
                            ?>
                            <i class="fa fa-level-down hidden-xs hidden-sm" aria-hidden="true"></i>
                        </div>
                        <div>
                            <?=
                            $this->Form->control('smart[' . $key . '][url]', [
                                'label' => false,
                                'class' => 'form-control input-sm',
                                'type' => 'text',
                                'autocomplete' => 'off',
                                'placeholder' => __('Destination URL'),
                                'value' => $smart['url']
                            ]);
                            ?>
                        </div>
                    </td>
                    <td class="actions">
                        <span class="handle" data-toggle="tooltip" data-placement="left"
                              title="<?= __('Move') ?>">
                            <i class="fa fa-arrows" aria-hidden="true"></i>
                        </span>
                        <br>
                        <a href="#" class="delete-target" data-toggle="tooltip" data-placement="left"
                           title="<?= __('Delete') ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            </table>
        </div>

        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
