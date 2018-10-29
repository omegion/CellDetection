import numpy as np
import cv2
import sys
import time

# Load an color image in grayscale
# img = cv2.imread('messi5.jpg',0)
img = np.zeros((100,100,3), np.uint8)

filename = sys.argv[1]
image = cv2.imread('../storage/app/public/images/'+str(filename))

width, height = image.shape[0:2]

cv2.putText(image, 'ASDASD', (int(width/2), int(height/2)), cv2.FONT_HERSHEY_DUPLEX, 1, (255,255,255))

cv2.imwrite('../storage/app/public/images/'+filename,image)


print(7)