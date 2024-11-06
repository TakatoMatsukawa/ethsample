import Mirador from 'mirador';

let miradorInstance = null;

document.addEventListener('DOMContentLoaded', function () {
    // Laravelから渡されたデータにアクセス
    const manifest = window.LaravelData;

    // Miradorの設定に渡されたデータを使用
    const config = {
        id: 'mirador',
        language: "ja",
        windows: [
            {
                loadedManifest: manifest,
            }
        ],
    };

    miradorInstance = Mirador.viewer(config);

});

export default miradorInstance;
