import pyheif
from PIL import Image
from time import sleep

def convert_heic_to_jpg(heic_file_path, jpg_file_path, quality=85, resize_factor=None):
    
    sleep(10)
    print("ok")
   
# Example usage for low quality and smaller size
convert_heic_to_jpg("ekramul-hasan.HEIC", "output_low_quality_resized.jpg", quality=25, resize_factor=0.2)  # Resize to 50%



