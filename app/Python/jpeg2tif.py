import pyvips
import os
import sys

args = sys.argv
input_image = args[1]  # 読み取り画像
iiif_images_directory= args[2]  # tifファイルを保存するディレクトリ
unique_id = args[3] # ユニークID

target_directory = iiif_images_directory + unique_id

# ディレクトリが存在しない場合は作成
if not os.path.exists(target_directory):
    os.makedirs(target_directory)

file_count = len([name for name in os.listdir(target_directory) if os.path.isfile(os.path.join(target_directory, name))])

file_number = str(file_count + 1).zfill(4)

try:
    ins = pyvips.Image.new_from_file(input_image)
    ins.tiffsave(target_directory + '/' + file_number + '.tif',
        tile=True,
        compression='jpeg',
        pyramid=True,
        tile_width=256,
        tile_height=256)
    print('finish')
except Exception as e:
    print(f"error: {str(e)}")
