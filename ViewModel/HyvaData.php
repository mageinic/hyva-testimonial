<?php
/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package Hyva_MageINICTestimonial
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace Hyva\MageINICTestimonial\ViewModel;

use MageINIC\Testimonial\Helper\Data;
use Magento\Framework\Serialize\SerializerInterface as Serializer;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Framework\View\Asset\Repository;

/**
 * Provides the user data to fill the form.
 */
class HyvaData implements ArgumentInterface
{
    /**
     * @var Data
     */
    protected Data $helperData;
    /**
     * @var Serializer
     */
    protected Serializer $serializer;
    /**
     * @var Repository
     */
    private Repository $assetRepos;
    /**
     * @var ImageFactory
     */
    private ImageFactory $imageFactory;

    /**
     * HyvaData Constructor
     *
     * @param Repository $assetRepos
     * @param ImageFactory $imageFactory
     * @param Data $helperData
     * @param Serializer $serializer
     */
    public function __construct(
        Repository   $assetRepos,
        ImageFactory $imageFactory,
        Data         $helperData,
        Serializer   $serializer
    ) {
        $this->assetRepos = $assetRepos;
        $this->imageFactory = $imageFactory;
        $this->helperData = $helperData;
        $this->serializer = $serializer;
    }

    /**
     * Get placeholder image of a product for small_image
     *
     * @return string
     */
    public function getPlaceholderImg(): string
    {
        $imagePlaceholder = $this->imageFactory->create();
        $smallImagePlaceHolder = $imagePlaceholder->getPlaceholder('small_image');
        return $this->assetRepos->getUrl($smallImagePlaceHolder);
    }

    /**
     * Wysiwyg Buttons Data
     *
     * @return array
     */
    public function getWysiwygButtons(): array
    {
        return [
            ['bold', null, 'mdi mdi-format-bold'],
            ['italic', null, 'mdi mdi-format-italic'],
            ['underline', null, 'mdi mdi-format-underline'],
            ['formatBlock', 'P', 'mdi mdi-format-paragraph'],
            ['formatBlock', 'H1', 'mdi mdi-format-header-1'],
            ['formatBlock', 'H2', 'mdi mdi-format-header-2'],
            ['formatBlock', 'H3', 'mdi mdi-format-header-3'],
            ['insertUnorderedList', null, 'mdi mdi-format-list-bulleted'],
            ['insertOrderedList', null, 'mdi mdi-format-list-numbered'],
            ['justifyLeft', null, 'mdi mdi-format-align-left'],
            ['justifyCenter', null, 'mdi mdi-format-align-center'],
            ['justifyRight', null, 'mdi mdi-format-align-right'],
        ];
    }

    /**
     * Receive Hyva Slider Breakpoints.
     *
     * @return string
     */
    public function getHyvaSliderBreakPoints(): string
    {
        $breakpoints = $this->helperData->getBreakpointConfig();
        $breakpoints = $this->serializer->unserialize($breakpoints);

        $values = [];
        foreach ($breakpoints as $breakpoint) {
            $values[] = [
                "breakpoint" => (int)$breakpoint['breakpoint'],
                "settings" => [
                    "slidesToShow" => (int)$breakpoint['slides_to_show'],
                    "slidesToScroll" => (int)$breakpoint['slides_to_scroll']
                ],
            ];
        }

        return $this->serializer->serialize($values);
    }

    /**
     * Manage HTML Tags from the Testimonial Content
     *
     * @param string $content
     * @return string
     */
    public function manageHtmlTag(string $content): string
    {
        return strip_tags($content);
    }
}
