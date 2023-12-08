import tensorflow as tf
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Conv2D, MaxPooling2D, Flatten, Dense

def testModel(model, test_folder,batch_size, shuffle_value):

    test_datagen = ImageDataGenerator(rescale=1./255)

    test_generator = test_datagen.flow_from_directory(
        test_folder,
        target_size=(256, 256),
        batch_size=batch_size,
        class_mode='categorical',
        shuffle=shuffle_value, 
    )
    # Evaluate the model on the test set
    evaluation = model.evaluate(test_generator)
    #print(f"Test Accuracy: {evaluation[1]*100:.2f}%")

    return evaluation[1]*100,test_generator
