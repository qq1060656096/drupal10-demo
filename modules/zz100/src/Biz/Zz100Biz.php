<?php

namespace Drupal\zz100\Biz;

use Drupal\Core\Language\LanguageInterface;

class Zz100Biz
{
  public function getLanguages()
  {
    // 获取语言管理器
    $languageManager = \Drupal::languageManager();

    // 获取指定语言码（例如：'en'）
    $languageCode = 'en';

    // 获取指定语言的语言对象
    $language = $languageManager->getLanguage($languageCode);

    // 获取该语言的语言列表
    $languages = $languageManager->getLanguages(LanguageInterface::STATE_CONFIGURABLE);
    $defaultLanguageArr = $languageManager->getDefaultLanguage();
    $languagesArr = [];
    foreach ($languages as $language) {
      $selected = false;
      if ($language->getId() == $languageCode) {
        $selected = true;
      }
      $rowTmp = [
        'id' => $language->getId(),
        'name' => $language->getName(),
        'desc' => $language->getDirection(),
        'weight' => $language->getWeight(),
        'is_default' => $language->isDefault(),
        'is_selected' => $selected,
      ];
      if ($language->isDefault()) {
        $defaultLanguageArr = $rowTmp;
      }
      $languagesArr[] = $rowTmp;
    }
    $data = [
      'default_language' => $defaultLanguageArr,
      'languages' => $languagesArr,
    ];
    return $data;
  }

  public function getDefault()
  {
    $data = $this->getLanguages();
    return $data;
  }
}
