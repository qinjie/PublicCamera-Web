import cv2
import numpy as np
import sys

def CrowdIdxCal(str_path_Bgd,str_path_Img):
    print str_path_Bgd,str_path_Img
    #parameters for a linear model
    Slope_opt = 0.01
    Intersection_opt = 6

    #threshold for pixel detection in binary pic
    threshold = 150

    #background image
    img_Bgd = cv2.imread(str_path_Bgd)
    #verification
    if img_Bgd is None:
        print 'Failed to load the background image!'
        sys.exit()
        
    #covert the image to gray scale
    img_Bgd = cv2.cvtColor(img_Bgd,cv2.COLOR_BGR2GRAY)    
    #reduce the light changing effect
    cv2.equalizeHist(img_Bgd)
    #detect objects of interest
    _, contours_Bgd, hierarchy = cv2.findContours(img_Bgd,cv2.RETR_TREE,cv2.CHAIN_APPROX_SIMPLE)
    cv2.drawContours(img_Bgd,contours_Bgd,-1,(0,255,0),3)


    #current image
    img_Cur = cv2.imread(str_path_Img)
    #verification
    if img_Cur is None:
        print 'Failed to load the current image!'
        sys.exit()
    #covert the image to gray scale
    img_Cur = cv2.cvtColor(img_Cur,cv2.COLOR_BGR2GRAY)
    #reduce the light changing effect
    cv2.equalizeHist(img_Cur)
    #detect objects of interest
    _, contours_Cur, hierarchy = cv2.findContours(img_Cur,cv2.RETR_TREE,cv2.CHAIN_APPROX_SIMPLE)
    cv2.drawContours(img_Cur,contours_Cur,-1,(0,255,0),3)


    #get foreground image
    img_Fore = img_Cur - img_Bgd
    ret,img_Thresh = cv2.threshold(img_Fore, threshold,255,cv2.THRESH_BINARY)
    
    #count number of pixels in fore image
    rows, cols = img_Thresh.shape[:2]

    PixelCnt = 0
    for i in range (0,rows):
        for j in range (0, cols):
            grayPixel = img_Thresh.item(i,j)
            if (grayPixel > 0):
                PixelCnt=PixelCnt+1

    #map the number of pixels in fore image to the number of people
    #based on the linear model

    CrowdIdx = int(Slope_opt*PixelCnt + Intersection_opt)

    _Version_ = 1.0

    return CrowdIdx

if __name__ == '__main__':

	#str_path_Bgd = "d:/GoogleDrive/Sites/publiccamera/upload/reference/0002.jpg"
	#str_path_Img = "d:/GoogleDrive/Sites/publiccamera/upload/files/0002_20160119_000911_81238700.jpg"
	str_path_Bgd = sys.argv[1]
	str_path_Img = sys.argv[2]
	
	CrowdIdx = CrowdIdxCal(str_path_Bgd,str_path_Img)
	#print 'Estimate number of people is ', CrowdIdx
	print CrowdIdx

