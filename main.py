import pyheif
from PIL import Image

def convert_heic_to_jpg(heic_file_path, jpg_file_path, quality=85, resize_factor=None):
    
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
    
    # Resize image if resize_factor is provided
    if resize_factor:
        new_size = (int(image.width * resize_factor), int(image.height * resize_factor))
        image = image.resize(new_size, Image.Resampling.LANCZOS)  # Use LANCZOS for high-quality resampling
        print(f"Resized image to: {new_size}")
    
    # Save as JPEG with the specified quality
    image.save(jpg_file_path, "JPEG", quality=quality, optimize=True, progressive=True)
    print(f"Saved {jpg_file_path} with quality={quality}")

# Example usage for low quality and smaller size
convert_heic_to_jpg("ekramul-hasan.HEIC", "output_low_quality_resized.jpg", quality=25, resize_factor=0.2)  # Resize to 50%



