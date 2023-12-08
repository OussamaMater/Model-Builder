import os
import random
import shutil

def splitDataSetIntoTrainAndTest(datasetPath, test_set_ratio):
# Set the path to your main data folder
    main_data_path = datasetPath

    # Create directories for the training and testing sets
    train_data_path = 'train'
    test_data_path = 'test'

    os.makedirs(train_data_path, exist_ok=True)
    os.makedirs(test_data_path, exist_ok=True)

    # Set the ratio of images to move to the testing set
    test_set_ratio = test_set_ratio

    # Loop through each class folder in the main data folder
    for class_folder in os.listdir(main_data_path):
        class_path = os.path.join(main_data_path, class_folder)

        # Create corresponding class directories in the training and testing sets
        train_class_path = os.path.join(train_data_path, class_folder)
        test_class_path = os.path.join(test_data_path, class_folder)

        os.makedirs(train_class_path, exist_ok=True)
        os.makedirs(test_class_path, exist_ok=True)

        # List all files in the class folder
        files = os.listdir(class_path)

        # Calculate the number of files to move to the testing set
        num_files_to_move = int(len(files) * test_set_ratio)

        # Randomly select files for the testing set
        files_to_move = random.sample(files, num_files_to_move)

        # Move selected files to the testing set directory
        for file_name in files_to_move:
            source_path = os.path.join(class_path, file_name)
            destination_path = os.path.join(test_class_path, file_name)

            # Check if the file exists before attempting to move
            if os.path.exists(source_path):
                shutil.move(source_path, destination_path)
            else:
                print(f"Warning: File does not exist at {source_path}. Skipping.")

        # Move the remaining files to the training set directory
        for file_name in files:
            source_path = os.path.join(class_path, file_name)
            destination_path = os.path.join(train_class_path, file_name)

            # Check if the file exists before attempting to move
            if os.path.exists(source_path):
                shutil.move(source_path, destination_path)
            else:
                print(f"Warning: File does not exist at {source_path}. Skipping.")

    print("Dataset split into training and testing sets for each class.")
