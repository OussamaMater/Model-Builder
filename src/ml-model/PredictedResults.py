import tensorflow as tf
from tensorflow.keras.preprocessing import image
from sklearn.metrics import confusion_matrix, classification_report
import matplotlib.pyplot as plt
import seaborn as sns
import numpy as np
from PIL import Image

def predicted_result_and_confusion_matrix(model, test_generator,image_to_predict, modelId):
# Load and preprocess the input image you want to predict
    new_image_path = image_to_predict  # Replace with the path to your new image
    img = image.load_img(new_image_path, target_size=(256, 256))
    img_array = image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)
    img_array /= 255.0

    # Make predictions
    true_labels = test_generator.classes

    # Make predictions on the test set
    predictions = model.predict(test_generator)

    # Get the predicted class indices
    predicted_class_indices = np.argmax(predictions, axis=1)

    # Get the class labels
    class_labels = test_generator.class_indices

    # Compute confusion matrix
    conf_matrix = confusion_matrix(true_labels, predicted_class_indices)

    print(conf_matrix)
    # Plot confusion matrix
    plt.figure(figsize=(8, 6))
    sns.heatmap(conf_matrix, annot=True, fmt='d', cmap='Blues', xticklabels=class_labels.keys(), yticklabels=class_labels.keys())
    plt.xlabel('Predicted')
    plt.ylabel('True')
    plt.title('Confusion Matrix')
    filename = f'confusion_matrix_{modelId}.png'
    plt.gcf().set_size_inches(8, 6.73)
    plt.savefig(filename, dpi=100, bbox_inches='tight')

    new_width, new_height = 800, 673
    existing_image_path = filename
    existing_image = Image.open(existing_image_path)
    resized_image = existing_image.resize((new_width, new_height))
    resized_image.save(filename)

    class_report = classification_report(true_labels, predicted_class_indices, target_names=class_labels.keys())
   
    img_predictions = model.predict(img_array)

    predicted_class_index = np.argmax(img_predictions)

    # Map the index to the corresponding class label
    class_labels = test_generator.class_indices
    predicted_class_label = [k for k, v in class_labels.items() if v == predicted_class_index][0]

    return conf_matrix, predicted_class_label