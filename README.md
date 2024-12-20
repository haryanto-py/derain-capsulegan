# Single Image Deraining using CapsuleGAN

This project is part of an undergraduate thesis in computer engineering at Universitas Pendidikan Indonesia. It demonstrates a novel approach to image rain removal using a fusion of Capsule Networks (CapsNet) and Generative Adversarial Networks (GAN) by embedding the CapsNet in the GAN’s discriminator.

## Project Overview

The CapsuleGAN model leverages the spatial awareness of CapsNet to understand relationships between local details and larger objects in the image, making it more effective than traditional CNN-based models. Experimental results show that combining CapsNet and GAN architectures produces derained images with higher fidelity compared to many conventional deep learning models. However, the model still faces some limitations, such as residual blur effects and faint rain streaks due to occasional instability in the training process.

![Pages from PPT Sidang Haryanto](https://github.com/user-attachments/assets/f9bc191c-01aa-4592-97b3-4c596c5f9439)

## Why This Project is Useful

This project provides a foundational approach for developing enhanced image deraining models. By exploring the combination of CapsNet and GAN architectures, future research can build upon this work to address the residual artifacts and optimize model performance.

## Getting Started

To run the project locally, follow these steps:

1. **Clone this Repository**:
   ```bash
   git clone <https://github.com/haryanto-py/derain-capsulegan.git>
   cd <derain-capsulegan>

2. **Change directories**
- Open index.php and change this line to corresponding path
![image](https://github.com/user-attachments/assets/8c0e5e22-e0bb-40fc-8083-9a85a9950cc3)
- Open synthetic.php and do the same thing
![image](https://github.com/user-attachments/assets/ade12a63-a65d-4b03-ac30-dee93d80b8cc)

3. **Start XAMPP Server**:
- Launch the XAMPP server on your computer to run a local server environment.

4. **Run the Project**:
- Open index.php on your local server to launch the deraining application.
- Upload a rain image to be tested.

5. **View Results**:
- Test images (real and synthetic) are available in the uploads folder.
- After processing, output images will be saved in the outputs folder.

## Snapshots
- Real rain image de-raining page
![image](https://github.com/user-attachments/assets/5685ffdf-ca92-4528-810a-d1cb6dedc00e)

- Synthetic rain image de-raining page (1)
![image](https://github.com/user-attachments/assets/2ee93542-7ef3-4d33-87c7-21c44aa895f4)

- Synthetic rain image de-raining page (2)
![image](https://github.com/user-attachments/assets/6f832f2c-b8d8-415d-9d5c-568ba51e461b)

## Citation
If you use this project as part of your research or development, please cite it as follows:

Hidayat, H., Munawir, M., Putra, M. T., & Satyawan, A. S. (2024). Pengembangan model CapsuleGAN untuk penghapusan hujan citra tunggal. JIPI (Jurnal Ilmiah Penelitian Dan Pembelajaran Informatika), 9(2), 1001–1012. https://doi.org/10.29100/jipi.v9i2.5534
