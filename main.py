import pyheif
from PIL import Image
import sys
import os

def convert_heic_to_jpg(heic_file_path, jpg_file_path, quality=85, resize_factor=None):
    ext = os.path.splitext(heic_file_path)[1].lower()

    

    if ext == ".heic":
        # Read the HEIC file
        heif_file = pyheif.read(heic_file_path)
        # Convert to a Pillow Image
        image = Image.frombytes(
            heif_file.mode, 
            heif_file.size, 
            heif_file.data,
            "raw",
            heif_file.mode,
            heif_file.stride,
        )
    elif ext in [".jpg", ".jpeg"]:
        # Open JPEG file
        image = Image.open(heic_file_path)
    else:
        raise ValueError(f"Unsupported file format: {ext}")    
    # Resize image if resize_factor is provided
    if resize_factor:
        new_size = (int(image.width * resize_factor), int(image.height * resize_factor))
        image = image.resize(new_size, Image.Resampling.LANCZOS)  # Use LANCZOS for high-quality resampling
        print(f"Resized image to: {new_size}")
    
    targetExt = os.path.splitext(jpg_file_path)[1].lower()
    if targetExt == '.png':
        sFormat = "PNG"
    else:
        sFormat = "JPEG"
    # Save as JPEG with the specified quality
    image.save(jpg_file_path, sFormat, quality=quality, optimize=True, progressive=True)
    print(f"Saved {jpg_file_path} with quality={quality}")

# Example usage for low quality and smaller size
convert_heic_to_jpg(f"{sys.argv[1]}", f"{sys.argv[3]}", quality=95)
convert_heic_to_jpg(f"{sys.argv[1]}", f"{sys.argv[2]}", quality=25, resize_factor=0.1)  # Resize to 50%




