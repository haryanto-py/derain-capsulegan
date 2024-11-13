import os
import tensorflow as tf
import keras
from tensorflow.keras.utils import img_to_array
import numpy as np
import cv2
import sys
import json
import warnings
import logging

# Suppress TensorFlow logs
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'
tf.get_logger().setLevel(logging.ERROR)
warnings.filterwarnings("ignore", message="No training configuration found in the save file")

from skimage.metrics import peak_signal_noise_ratio as compare_psnr
from skimage.metrics import structural_similarity as compare_ssim

# final
generator_model = tf.keras.models.load_model("CapsuleGAN.h5")

def load_and_preprocess_image(image_path, size=256):
    try:
        img = cv2.imread(image_path, cv2.IMREAD_COLOR)
        img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)

        height, width, _ = img.shape
        
        crop_size = min(height, width)
        
        start_x = (width - crop_size) // 2
        start_y = (height - crop_size) // 2
        
        img_cropped = img[start_y:start_y + crop_size, start_x:start_x + crop_size]
        
        img_resized = cv2.resize(img_cropped, (size, size))
        
        img_resized = img_resized.astype('float32') / 255.0
        
        img_array = img_to_array(img_resized)
        img_array = np.expand_dims(img_array, axis=0)
        
        return img_array
    except Exception as e:
        print(f"Error loading and preprocessing image: {e}")
        raise

def process_image(image_path, target_path=None, model=generator_model):
    try:
        image = load_and_preprocess_image(image_path)

        output_image = model(image, training=True)
        output_image = np.clip(output_image, 0, 1)
        output_image = np.squeeze(output_image, axis=0)
        output_image = (output_image * 255).astype(np.uint8)

        # Get the name and extension of the input image
        image_name, image_ext = os.path.splitext(os.path.basename(image_path))

        # Construct the output image path
        output_name = f"{image_name}_output{image_ext}"
        output_path = os.path.join("C:/xampp3/htdocs/model_test/outputs/", output_name)

        result = {"output_path": output_path}

        if target_path is not None:
            target_image = load_and_preprocess_image(target_path)
            target_image = np.squeeze(target_image, axis=0)

            psnr = float(compare_psnr(target_image, output_image / 255.0, data_range=1))
            ssim = float(compare_ssim(target_image, output_image / 255.0, data_range=1, channel_axis=-1, win_size=11))

            psnr = format(psnr, ".2f")
            ssim = format(ssim, ".3f")
            
            result["psnr"] = psnr
            result["ssim"] = ssim

        cv2.imwrite(output_path, cv2.cvtColor(output_image, cv2.COLOR_RGB2BGR))

        return json.dumps(result)

    except Exception as e:
        error_message = f"Error processing image: {str(e)}"
        return json.dumps({"error": error_message})

if __name__ == "__main__":
    if len(sys.argv) != 2 and len(sys.argv) != 3:
        error_message = "Usage: python process_image.py <image_path> [target_path]"
        sys.exit(1)

    image_path = sys.argv[1]

    if len(sys.argv) == 3:
        target_path = sys.argv[2]
    else:
        target_path = None

    result = process_image(image_path, target_path)
    print(result)
