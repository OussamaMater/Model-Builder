import tensorflow as tf
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Conv2D, MaxPooling2D, Flatten, Dense
from tensorflow.keras.models import save_model
from tensorflow.keras.models import load_model

import requests

def trainModel(trainFolder, epochs, batch_size, shuffle_value, model_id, user_id):
# Set the dimensions of your images
    img_width, img_height = 256, 256

    print(trainFolder)
    print(epochs)
    print(batch_size)
    print(shuffle_value)
    print(model_id)
    print(user_id)

    # Specify the path to your training and testing data
    train_data_dir = trainFolder
    # Create a data generator for training
    train_datagen = ImageDataGenerator(rescale=1./255)

    train_generator = train_datagen.flow_from_directory (
        train_data_dir,
        target_size=(img_width, img_height),
        batch_size=batch_size,
        shuffle=shuffle_value,
        class_mode='categorical' 
    )

    # Create a simple convolutional neural network (CNN) model
    model = Sequential()
    model.add(Conv2D(32, (3, 3), activation='relu', input_shape=(img_width, img_height, 3)))
    model.add(MaxPooling2D((2, 2)))
    model.add(Conv2D(64, (3, 3), activation='relu'))
    model.add(MaxPooling2D((2, 2)))
    model.add(Conv2D(128, (3, 3), activation='relu'))
    model.add(MaxPooling2D((2, 2)))
    model.add(Flatten())
    model.add(Dense(128, activation='relu'))
    model.add(Dense(3, activation='softmax'))  # Adjust based on the number of classes

    # Compile the model
    print("compiling...")
    model.compile(optimizer='adam', loss='categorical_crossentropy', metrics=['accuracy'])

    # Train the model
    print("training...")
    model.fit(train_generator, epochs=epochs)

    print("caching...")
    filename = f'cached_model_{model_id}'
    save_model_to_file(model, filename)

    print("pinging user..")
    modelReady(user_id, model_id)

    return model

def save_model_to_file(model, filename):
    save_model(model, filename) 

def load_model_from_file(filename):
    model = load_model(filename)
    return model

def modelReady(user_id, model_id):
    print("Pinged user")
    url = f"http://127.0.0.1:8000/api/model-ready/{model_id}"
    params = {'user_id': user_id}
    response = requests.get(url, params=params)
    print(response)
