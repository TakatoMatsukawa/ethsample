<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;

/**
 * IIIFマニフェストを作成する関数
 */
class CreateIIIFManifestJson
{
    /**
     *
     * @param string $iiifCode iiifのコード
     * @param array $iiifimages iiif一覧
     * @param int $page iiifのページ数
     * @param string $frontURL フロントのURL
     * @param string $label LABELに出力する値
     * @param array $metadata メタデータとして出力する配列
     * @param string $license ライセンス
     * @return void
     */
    public function CreateIIIFManifestJson(string $iiifCode, array $iiifimages, int $page, string $frontURL, string $label, array $metadata, string $license): void
    {
        // canvasの空配列作成
        $canvases = [];
        // IIIFの数だけcanvasデータを作成
        foreach ($iiifimages as $key => $value) {
            $iiifCode = (string) $iiifCode;
            // サイズ取得
            $imagePath = Storage::disk('iiifimages_directory')->path($value);
            list($width, $height) = getimagesize($imagePath);

            $canvas = [
                // tifファイルのディレクトリ
                '@id' => $frontURL . '/view/' . $iiifCode . '/canvas/p' . $key + 1,
                '@type' => 'sc:Canvas',
                // ページ数
                'label' => 'p. ' . $key + 1,
                // 画像幅(px)
                'width' => $width,
                // 画像高さ(px)
                'height' => $height,
                'images' => [
                    [
                        '@type' => 'oa:Annotation',
                        'motivation' => 'sc:painting',
                        'resource' => [
                            // IIIFURL
                            '@id' => $frontURL . '/view/' . $value . '/full/full/0/default.jpg',
                            '@type' => 'dctypes:Image',
                            'format' => 'image/jpeg',
                            // 画像幅(px)
                            'width' => $width,
                            // 画像高さ(px)
                            'height' => $height,
                            'service' => [
                                '@context' => 'http://iiif.io/api/image/2/context.json',
                                // tiffファイルURL
                                '@id' => $frontURL . '/view/' . $value,
                                'profile' => 'http://iiif.io/api/image/2/level1.json',
                            ],
                        ],
                        // canvasと同一
                        'on' => $frontURL . '/view/' . $iiifCode . '/canvas/p' . $key + 1,
                    ],
                ],
            ];
            array_push($canvases, $canvas);
        }
        // jsonデータ作成
        $jsonData = [
            '@context' => 'http://iiif.io/api/presentation/2/context.json',
            '@type' => 'sc:Manifest',
            // manifestURL
            '@id' => $frontURL . '/' . $iiifCode . '/manifest',
            'label' => $label,
            'metadata' => $metadata,
            'viewingDirection' => 'right-to-left',
            'viewingHint' => 'paged',
            // DBと利用規約ページURL
            'license' => $license,
            'attribution' => '佐川町立図書館 Sakawa Town Library, JAPAN',
            // ロゴURL
            'logo' => $frontURL . '/assets/img/logo.png',
            'sequences' => [
                [
                    '@type' => 'sc:Sequence',
                    'canvases' => $canvases,
                ],
            ],
            'structures' => [
                [
                    // tifのディレクトリ + range/r0
                    '@id' => $frontURL . '/view/' . $iiifCode . '/range/r0',
                    '@type' => 'sc:Range',
                    'label' => '最初のページ',
                    // 1ページ目のcanvas
                    'canvases' => [$frontURL . '/view/' . $iiifCode . '/canvas/p1'],
                ],
                [
                    // tifのディレクトリ + range/r1
                    '@id' => $frontURL . '/view/' . $iiifCode . '/range/r1',
                    '@type' => 'sc:Range',
                    'label' => '最後のページ',
                    // 最後のページのcanvas
                    'canvases' => [$frontURL . '/view/' . $iiifCode . '/canvas/p' . $page],
                ],
            ],
        ];

        Storage::put('iiifmanifests/' . $iiifCode . '.json', json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
