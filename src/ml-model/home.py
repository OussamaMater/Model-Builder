from fastapi import FastAPI, File, Form, UploadFile
from fastapi.responses import JSONResponse
from splitDatasetTrainTest import *
import shutil
import zipfile
from ModelTrain import *
from ModelTest import *
from PredictedResults import *
import requests
from datetime import datetime

app = FastAPI()

def upload_zip():
    @app.post("/upload-zip")
    async def upload_zip(
        zip_file: UploadFile = File(...),
        ratio: float = Form(...),
        epochs: int = Form(...),
        batch_size: int = Form(...),
        userId: int = Form(...),
        modelId: int = Form(...),
    ):
        try:
            with open('archive.zip', 'wb') as f:
                shutil.copyfileobj(zip_file.file, f)
            with zipfile.ZipFile('archive.zip', 'r') as zip_ref:
                zip_ref.extractall('images')
                splitDataSetIntoTrainAndTest('images', ratio)
                trainModel('train', epochs, batch_size, False, modelId, userId)
        except Exception as e:
            return JSONResponse(content={"status": "error", "message": str(e)})

def train_model():
    @app.post("/train-model")
    async def train_model(
        image: UploadFile = File(...),
        modelId: int = Form(...),
        batch_size: int = Form(...),
        userId: int = Form(...),
    ):
        try:
            started_at = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
            filepath = save_image(image, modelId)
            filename = f'cached_model_{modelId}'
            model = load_model_from_file(filename)
            accuracy, test_generator = testModel(model, 'test', batch_size, False)
            conf_matrix, predicted_class_label = predicted_result_and_confusion_matrix(model, test_generator, filepath, modelId)
            resultReady(userId, modelId, started_at)
            print(conf_matrix)
            print(predicted_class_label,accuracy)
            # now will send the result
        except Exception as e:
            return JSONResponse(content={"status": "error", "message": str(e)})


def save_image(image, modelId):
    upload_folder = 'test_images'
    os.makedirs(upload_folder, exist_ok=True)
    filename = f'uploaded_image_{modelId}.png'
    filepath = os.path.join(upload_folder, filename)
    
    with open(filepath, 'wb') as file:
        file.write(image.file.read())
    
    return filepath

def modelReady(user_id, model_id):
    print("Pinged user")
    url = f"http://127.0.0.1:8000/model-ready/{model_id}"
    params = {'user_id': user_id}
    requests.get(url, params=params)

def resultReady(user_id, model_id, startedAt):
    print("Sent Results")
    url = f"http://127.0.0.1:8000/api/results-ready/{model_id}"

    path = f'confusion_matrix_{model_id}.png'

    params = {
        'user_id': user_id, 
        'related_image': path, 
        'started_at': startedAt,
    }

    print(params)

    files = {'file': open(path, 'rb')}
    response = requests.post(url, data=params, files=files)
    print(response.text)

upload_zip()
train_model()